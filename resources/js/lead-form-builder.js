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
      } else {
        el = document.createElement('input');
        el.type = field.type;
      }
      el.className = 'form-control';
      wrapper.appendChild(el);

      const remove = document.createElement('button');
      remove.type = 'button';
      remove.className = 'btn btn-sm btn-danger mt-1';
      remove.textContent = 'Remove';
      remove.addEventListener('click', () => {
        fields.splice(index, 1);
        render();
      });
      wrapper.appendChild(remove);

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
    fields.push({ type, label: type.charAt(0).toUpperCase() + type.slice(1) });
    render();
  });
});
