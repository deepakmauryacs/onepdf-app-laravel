document.addEventListener('DOMContentLoaded', () => {
  const palette = document.getElementById('field-palette');
  const canvas = document.getElementById('builder-canvas');
  const input = document.getElementById('fields-input');

  let fields = window.existingFields || [];

  function render() {
    canvas.innerHTML = '';
    fields.forEach((field, index) => {
      const wrapper = document.createElement('div');
      wrapper.className = 'mb-3';

      const label = document.createElement('label');
      label.textContent = field.label || field.type;
      wrapper.appendChild(label);

      let el;
      if (field.type === 'textarea') {
        el = document.createElement('textarea');
        el.className = 'form-control';
        wrapper.appendChild(el);
      } else if (field.type === 'select') {
        el = document.createElement('select');
        el.className = 'form-select';
        (field.options || []).forEach(opt => {
          const option = document.createElement('option');
          option.value = opt;
          option.textContent = opt;
          el.appendChild(option);
        });
        wrapper.appendChild(el);
      } else if (field.type === 'radio') {
        el = document.createElement('div');
        (field.options || []).forEach(opt => {
          const radioWrap = document.createElement('div');
          const radio = document.createElement('input');
          radio.type = 'radio';
          radio.name = `field_${index}`;
          radio.value = opt;
          const rLabel = document.createElement('span');
          rLabel.textContent = ` ${opt}`;
          radioWrap.appendChild(radio);
          radioWrap.appendChild(rLabel);
          el.appendChild(radioWrap);
        });
        wrapper.appendChild(el);
      } else if (field.type === 'checkbox') {
        el = document.createElement('input');
        el.type = 'checkbox';
        el.className = 'form-check-input';
        wrapper.appendChild(el);
      } else {
        el = document.createElement('input');
        el.type = field.type;
        el.className = 'form-control';
        wrapper.appendChild(el);
      }

      const btnGroup = document.createElement('div');
      btnGroup.className = 'mt-1';

      const edit = document.createElement('button');
      edit.type = 'button';
      edit.className = 'btn btn-sm btn-secondary me-1';
      edit.textContent = 'Edit';
      edit.addEventListener('click', () => {
        const newLabel = prompt('Field label', field.label);
        if (newLabel !== null) field.label = newLabel;
        if (['select', 'radio'].includes(field.type)) {
          const current = (field.options || []).join(', ');
          const opts = prompt('Options (comma separated)', current);
          if (opts !== null) {
            field.options = opts.split(',').map(o => o.trim()).filter(o => o);
          }
        }
        render();
      });
      btnGroup.appendChild(edit);

      const remove = document.createElement('button');
      remove.type = 'button';
      remove.className = 'btn btn-sm btn-danger';
      remove.textContent = 'Remove';
      remove.addEventListener('click', () => {
        fields.splice(index, 1);
        render();
      });
      btnGroup.appendChild(remove);

      wrapper.appendChild(btnGroup);

      canvas.appendChild(wrapper);
    });
    input.value = JSON.stringify(fields);
  }

  render();

  palette.querySelectorAll('.draggable-field').forEach(el => {
    el.addEventListener('dragstart', e => {
      e.dataTransfer.setData('type', el.dataset.type);
    });
  });

  canvas.addEventListener('dragover', e => e.preventDefault());
  canvas.addEventListener('drop', e => {
    e.preventDefault();
    const type = e.dataTransfer.getData('type');
    const label = prompt('Field label', type.charAt(0).toUpperCase() + type.slice(1)) || type;
    const field = { type, label };
    if (['select', 'radio'].includes(type)) {
      const opts = prompt('Options (comma separated)', 'Option 1, Option 2');
      field.options = opts ? opts.split(',').map(o => o.trim()).filter(o => o) : [];
    }
    fields.push(field);
    render();
  });
});
