document.addEventListener('DOMContentLoaded', () => {

    /* ===== LISTA NEGRA ===== */
    window.toggleListaNegra = function (valor) {
        const bloque = document.getElementById("datos_lista_negra");
        if (!bloque) return;

        if (valor === "true") {
            bloque.style.display = "block";
        } else {
            bloque.style.display = "none";
        }
    };

    /* ===== RAZAS POR ESPECIE ===== */
    const especieSelect = document.querySelector('select[name="id_esp"]');
    const razaSelect    = document.getElementById('raza');

    if (!especieSelect || !razaSelect) {
        console.error("Selects no encontrados");
        return;
    }

    especieSelect.addEventListener('change', () => {
        const idEsp = especieSelect.value;

        razaSelect.innerHTML = '<option value="">Cargando razas...</option>';

        if (!idEsp) {
            razaSelect.innerHTML = '<option value="">Raza</option>';
            return;
        }

        fetch(`tipoRaza.php?id_esp=${idEsp}`)
            .then(res => {
                if (!res.ok) throw new Error("Error HTTP");
                return res.json();
            })
            .then(data => {
                razaSelect.innerHTML = '<option value="">Selecciona una raza</option>';

                if (data.length === 0) {
                    razaSelect.innerHTML +=
                        '<option value="">Sin razas registradas</option>';
                }

                data.forEach(r => {
                    razaSelect.innerHTML +=
                        `<option value="${r.id_raza}">${r.nombre}</option>`;
                });
            })
            .catch(err => {
                console.error(err);
                razaSelect.innerHTML =
                    '<option value="">Error al cargar razas</option>';
            });
    });

});
