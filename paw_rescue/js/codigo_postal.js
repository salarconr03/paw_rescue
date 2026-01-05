const cpInput = document.getElementById('codigo_postal');
const coloniaSelect = document.getElementById('asentamiento');
const municipioFinal = document.getElementById('municipio_final');
const municipioManual = document.getElementById('municipio_manual');

/* === CUANDO ESCRIBE CP === */
cpInput.addEventListener('input', () => {

  const cp = cpInput.value.trim();

  if (cp.length !== 5) {
    coloniaSelect.innerHTML = '<option value="">Colonia</option>';
    municipioFinal.value = '';
    return;
  }

  fetch('/paw_rescue/Admin/buscarCodigoPostal.php?cp=' + cp)
    .then(r => {
      if (!r.ok) throw new Error('Error HTTP');
      return r.json();
    })
    .then(data => {

      coloniaSelect.innerHTML = '<option value="">Colonia</option>';

      data.asentamientos.forEach(a => {
        coloniaSelect.innerHTML += `<option value="${a.id}">${a.nombre}</option>`;
      });

      municipioFinal.value = data.municipio;
      municipioManual.value = "";     // limpia selección manual
    })
    .catch(err => {
      console.error(err);
      municipioFinal.value = '';
    });
});

/* === CUANDO SELECCIONA MUNICIPIO MANUAL === */
municipioManual.addEventListener('change', () => {
  municipioFinal.value = municipioManual.value;
  cpInput.value = "";
  coloniaSelect.innerHTML = '<option value="">Colonia</option>';
});
