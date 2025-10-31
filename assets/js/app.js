const siteUrl = document.querySelector('meta[name="site_url"]').content;

function cargandoIcono(idElemento) {
  const elemento = document.getElementById(idElemento);
  if (elemento) {
    elemento.innerHTML = `
            <div style="display: flex; flex-direction: column; align-items: center;">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <span style="margin-top: 8px;">Cargando...</span>
            </div>
        `;
  }
}

function cargarEmpresasAsociadas() {
  //ModalCambiarEmpresa
  const modal = new bootstrap.Modal(
    document.getElementById("ModalCambiarEmpresa")
  );
  modal.show();
  cargandoIcono("empresasAsociadas");

  // Agregar event listener para el buscador
  const buscador = document.getElementById("buscadorEmpresas");
  if (buscador) {
    buscador.addEventListener("input", filtrarEmpresas);
  }
  fetch(siteUrl + "/api/auth/cambiar_empresa.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      accion: "listar_empresas",
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      const lista = document.getElementById("empresasAsociadas");
      if (lista) {
        lista.innerHTML = "";
        console.log(data);
        // data.data puede ser un array o un objeto
        let empresas = [];
        if (data.status === 200) {
          if (Array.isArray(data.data)) {
            empresas = data.data;
          } else if (data.data && typeof data.data === "object") {
            empresas = [data.data];
          }
          if (empresas.length > 0) {
            empresas.forEach((empresa) => {
              const item = document.createElement("li");
              item.className =
                "list-group-item d-flex justify-content-between align-items-center";
              item.innerHTML = `
                            <div class="d-flex flex-column flex-grow-1">
                                <span class="fw-medium">${empresa.nombre_legal}</span>
                                <small class="text-muted">Rol: ${empresa.rol}</small>
                            </div>
                            <button class="btn btn-outline-success btn-sm ms-2" onclick="confirmarCambioEmpresa(${empresa.id_empresa}, '${empresa.nombre_legal}')">
                                <i class="fas fa-check me-1"></i>Seleccionar
                            </button>
                        `;
              lista.appendChild(item);
            });
          } else {
            lista.innerHTML =
              '<li class="list-group-item text-center">No hay empresas asociadas.</li>';
          }
        } else {
          lista.innerHTML =
            '<li class="list-group-item text-center">No hay empresas asociadas.</li>';
        }
      } else {
        console.error(
          'Elemento con id "empresasAsociadas" no encontrado en el DOM.'
        );
      }
    })
    .catch((error) => {
      const lista = document.getElementById("empresasAsociadas");
      if (lista) {
        lista.innerHTML =
          '<li class="list-group-item text-danger text-center">Error al cargar empresas.</li>';
      }
      console.error("Error al obtener empresas asociadas:", error);
    });
}

function confirmarCambioEmpresa(idEmpresa, nombreEmpresa) {
  Swal.fire({
    title: "¿Cambiar empresa?",
    text: `¿Estás seguro de que deseas cambiar a ${nombreEmpresa}?`,
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#198754",
    cancelButtonColor: "#dc3545",
    confirmButtonText: "Sí, cambiar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      seleccionarEmpresa(idEmpresa);
    }
  });
}

function filtrarEmpresas() {
  const buscador = document.getElementById("buscadorEmpresas");
  if (buscador) {
    const filtro = buscador.value.toLowerCase().trim();
    const empresas = document.querySelectorAll(
      "#empresasAsociadas li.list-group-item"
    );
    empresas.forEach(function (empresa) {
      // Solo filtrar elementos que tengan botón de seleccionar (empresas reales)
      const botonSeleccionar = empresa.querySelector(
        'button[onclick*="seleccionarEmpresa"]'
      );
      if (botonSeleccionar) {
        const nombreEmpresa = empresa.querySelector(".fw-medium");
        const textoEmpresa = nombreEmpresa
          ? nombreEmpresa.textContent.toLowerCase()
          : "";
        if (filtro === "" || textoEmpresa.includes(filtro)) {
          empresa.style.display = "flex";
          empresa.style.visibility = "visible";
        } else {
          empresa.style.display = "none";
          empresa.style.visibility = "hidden";
        }
      }
    });
  }
}

function seleccionarEmpresa(idEmpresa) {
  fetch(siteUrl + "/api/auth/cambiar_empresa.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      accion: "cambiar_empresa",
      nueva_empresa_id: idEmpresa,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === 200) {
        notify("Empresa cambiada correctamente", "success");
        location.reload(); // Recargar la página para reflejar los cambios
      } else {
        notify(data.message || "Error al cambiar empresa", "error");
      }
    })
    .catch((error) => {
      console.error("Error al cambiar empresa:", error);
      notify("Error al cambiar empresa", "error");
    });
}

function notify(mensaje, tipo = "info") {
  if (window.Swal) {
    Swal.fire({
      icon: tipo,
      title: mensaje,
      timer: 1800,
      showConfirmButton: false,
    });
  } else {
    alert(mensaje);
  }
}

// SweetAlert helpers
const swalOk = (msg) =>
  Swal.fire({
    icon: "success",
    title: "Listo",
    text: msg,
    timer: 1600,
    showConfirmButton: false,
  });
const swalError = (msg) =>
  Swal.fire({
    icon: "error",
    title: "Error",
    text: msg,
  });
const swalConfirm = async (title, text, confirmText = "Sí, continuar") => {
  const r = await Swal.fire({
    icon: "warning",
    title,
    text,
    showCancelButton: true,
    confirmButtonText: confirmText,
    cancelButtonText: "Cancelar",
  });
  return r.isConfirmed;
};

function swalLoading(mensaje) {
    Swal.fire({
        title: mensaje || 'Procesando...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}