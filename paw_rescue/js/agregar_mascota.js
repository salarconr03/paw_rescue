const selectEspecie = document.getElementById('especie');
const vacunasPerro = document.querySelectorAll('.perro');
const vacunasGato = document.querySelectorAll('.gato');

function ocultarTodas() {
    vacunasPerro.forEach(v => v.style.display = 'none');
    vacunasGato.forEach(v => v.style.display = 'none');
}

ocultarTodas();

selectEspecie.addEventListener('change', (e) => {
    ocultarTodas();
    if (e.target.value === 'perro') {
    vacunasPerro.forEach(v => v.style.display = 'block');
    } else if (e.target.value === 'gato') {
    vacunasGato.forEach(v => v.style.display = 'block');
    }
});