import{g as B}from"./gridjs.umd-V6YQZSHU.js";import"./_commonjsHelpers-BosuxZz1.js";document.addEventListener("DOMContentLoaded",function(){var v,y;if(typeof window.empresasData>"u"){console.error("No se encontraron datos de empresas.");return}const f=window.empresasData.map(t=>[t.nombre,t.direccion,t.descripcion,t.latitud,t.longitud,t.radio,t.fichaje_activo,t.id]);new B.Grid({columns:["Nombre","Dirección","Descripción","Latitud","Longitud","Radio",{name:"Fichaje activo",formatter:(t,e)=>e.cells[6].data==1?"Sí":"No"},{name:"Acciones",formatter:(t,e)=>{const n=e.cells[7].data;return B.html(`
                        <div class="text-end">
                            <button
                                class="btn btn-sm btn-primary mb-2"
                                data-bs-toggle="modal"
                                data-bs-target="#editEmpresaModal"
                                data-id="${n}"
                                data-nombre="${e.cells[0].data}"
                                data-direccion="${e.cells[1].data}"
                                data-descripcion="${e.cells[2].data}"
                                data-latitud="${e.cells[3].data}"
                                data-longitud="${e.cells[4].data}"
                                data-radio="${e.cells[5].data}"
                                data-fichaje="${e.cells[6].data}">
                                Editar
                            </button>

                            <button
                                class="btn btn-sm btn-danger mb-2"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEliminarEmpresa"
                                data-empresa-id-delete="${n}">
                                Eliminar
                            </button>

                            <a href="/registrosFichajes/${n}" class="btn btn-sm btn-info mb-2">
                                Ver empleados
                            </a>
                        </div>
                    `)}}],data:f,search:!0,sort:!0,pagination:{enabled:!0,limit:5}}).render(document.getElementById("table-empresas"));let a=null,l=null,m=null,c=null;function p(t,e,n,s=40.4168,o=-3.7038){document.getElementById(t)&&(a&&(a.remove(),a=null),m=document.getElementById(e),c=document.getElementById(n),a=L.map(t).setView([s,o],15),L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",{attribution:"© OpenStreetMap"}).addTo(a),l=L.marker([s,o],{draggable:!0}).addTo(a),r(s,o),a.on("click",d=>{l.setLatLng(d.latlng),r(d.latlng.lat,d.latlng.lng)}),l.on("dragend",d=>{const I=d.target.getLatLng();r(I.lat,I.lng)}),setTimeout(()=>a.invalidateSize(),300))}function r(t,e){m&&c&&(m.value=t.toFixed(6),c.value=e.toFixed(6))}async function g(t){const e=document.getElementById(t);if(!e||!e.value.trim())return;const n=`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(e.value)}`,o=await(await fetch(n)).json();if(!o.length){alert("No se encontraron resultados");return}const i=parseFloat(o[0].lat),d=parseFloat(o[0].lon);a.setView([i,d],17),l.setLatLng([i,d]),r(i,d)}const E=document.getElementById("agregarEmpresa");E&&(E.addEventListener("shown.bs.modal",()=>{p("crearMapa","latitud","longitud")}),(v=document.getElementById("crearBuscarDireccion"))==null||v.addEventListener("click",()=>g("crearDireccionBusqueda")));const u=document.getElementById("editEmpresaModal");u&&(u.addEventListener("show.bs.modal",t=>{const e=t.relatedTarget;document.getElementById("modalEmpresaId").value=e.dataset.id,document.getElementById("modalEmpresaNombre").value=e.dataset.nombre,document.getElementById("modalEmpresaDireccion").value=e.dataset.direccion,document.getElementById("modalEmpresaDescripcion").value=e.dataset.descripcion,document.getElementById("modalEmpresaLatitud").value=e.dataset.latitud,document.getElementById("modalEmpresaLongitud").value=e.dataset.longitud,document.getElementById("modalEmpresaRadio").value=e.dataset.radio,document.getElementById("editEmpresaFichajeActivo").checked=e.dataset.fichaje==1,document.getElementById("editEmpresaForm").action=`/empresas/update/${e.dataset.id}`}),u.addEventListener("shown.bs.modal",()=>{p("editarMapa","modalEmpresaLatitud","modalEmpresaLongitud",parseFloat(document.getElementById("modalEmpresaLatitud").value),parseFloat(document.getElementById("modalEmpresaLongitud").value))}),(y=document.getElementById("editarBuscarDireccion"))==null||y.addEventListener("click",()=>g("editarDireccionBusqueda")));const b=document.getElementById("modalEliminarEmpresa");b&&b.addEventListener("show.bs.modal",t=>{const e=t.relatedTarget.dataset.empresaIdDelete;document.getElementById("deleteEmpresaId").value=e,document.getElementById("deleteEmpresaForm").action=`/empresas/delete/${e}`})});
