document.addEventListener('DOMContentLoaded', () => {
  const palette = document.getElementById('field-palette');
  const canvas = document.getElementById('builder-canvas');
  const input = document.getElementById('fields-input');
  const modalEl = document.getElementById('fieldModal');
  const modal = new window.bootstrap.Modal(modalEl);
  const form = modalEl.querySelector('form');
  const labelInput = document.getElementById('field-label');
  const optionsWrapper = document.getElementById('options-wrapper');
  const optionsInput = document.getElementById('field-options');

  let currentField = null;
  let currentIndex = null;

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
        openModal(field, index);
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
    const field = { type, label: type.charAt(0).toUpperCase() + type.slice(1) };
    openModal(field);
  });

  function openModal(field, index = null) {
    currentField = field;
    currentIndex = index;
    labelInput.value = field.label || '';
    if (['select', 'radio'].includes(field.type)) {
      optionsWrapper.style.display = '';
      optionsInput.value = (field.options || []).join(', ');
    } else {
      optionsWrapper.style.display = 'none';
      optionsInput.value = '';
    }
    modal.show();
  }

  form.addEventListener('submit', e => {
    e.preventDefault();
    currentField.label = labelInput.value || currentField.type;
    if (['select', 'radio'].includes(currentField.type)) {
      currentField.options = optionsInput.value
        .split(',')
        .map(o => o.trim())
        .filter(o => o);
    } else {
      delete currentField.options;
    }
    if (currentIndex === null) {
      fields.push(currentField);
    } else {
      fields[currentIndex] = currentField;
    }
    modal.hide();
    render();
  });
});
