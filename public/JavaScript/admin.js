// ===== CARGAR SECCIONES DINÁMICAS =====
function loadSection(sectionPath) {
    const content = document.getElementById('dynamicSection');
    if(!content) {
        console.error('No existe el contenedor #dynamicSection');
        return;
    }

    fetch(sectionPath)
        .then(res => {
            if(!res.ok) throw new Error('No se pudo cargar la sección');
            return res.text();
        })
        .then(html => {
            content.style.display = 'block';
            content.innerHTML = html;
        })
        .catch(err => {
            content.innerHTML = `<p style="color:red;">Error: ${err.message}</p>`;
        });
}

// ===== MODAL GLOBAL =====
function openModal(url) {
    const overlay = document.getElementById('modalOverlay');
    const content = document.getElementById('modalContent');

    fetch(url)
        .then(res => {
            if(!res.ok) throw new Error('No se pudo cargar el modal');
            return res.text();
        })
        .then(html => {
            content.innerHTML = html;
            overlay.style.display = 'flex';
        })
        .catch(err => {
            content.innerHTML = `<p style="color:red;">${err}</p>`;
            overlay.style.display = 'flex';
        });
}

function closeModal() {
    const overlay = document.getElementById('modalOverlay');
    overlay.style.display = 'none';
    document.getElementById('modalContent').innerHTML = '';
}
