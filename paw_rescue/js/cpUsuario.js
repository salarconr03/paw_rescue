document.addEventListener('DOMContentLoaded', () => {

  const cpInput = document.getElementById('codigo_postal');
  const coloniaSelect = document.getElementById('asentamiento');
  const municipioFinal = document.getElementById('municipio_final');

  if (!cpInput || !coloniaSelect || !municipioFinal) {
    console.error('Faltan elementos del formulario');
    return;
  }

  cpInput.addEventListener('input', () => {

    const cp = cpInput.value.trim();

    // Validación básica
    if (!/^\d{5}$/.test(cp)) {
      coloniaSelect.innerHTML = '<option value="">Selecciona colonia</option>';
      municipioFinal.value = '';
      return;
    }

    // ⚠️ RUTA CORREGIDA (Usuario con U mayúscula)
    fetch('/paw_rescue/Usuario/buscarCP.php?cp=' + cp)
      .then(res => {
        if (!res.ok) throw new Error('Error HTTP ' + res.status);
        return res.json();
      })
      .then(data => {

        coloniaSelect.innerHTML =
          '<option value="">Selecciona colonia</option>';

        if (!data.asentamientos || data.asentamientos.length === 0) {
          municipioFinal.value = '';
          return;
        }

        data.asentamientos.forEach(a => {
          coloniaSelect.innerHTML +=
            `<option value="${a.id}">${a.nombre}</option>`;
        });

        municipioFinal.value = data.municipio || '';
      })
      .catch(err => {
        console.error('Error CP:', err);
        coloniaSelect.innerHTML =
          '<option value="">Selecciona colonia</option>';
        municipioFinal.value = '';
      });
  });

});
