import { fetchHelper } from "../utils/fetch";

const formData = {
    idParcela: 0,
    cantidadCuotas: 0,
    precioTotalEntrega: 0,
    promedioCemento: 0,
};

const cuotas = document.getElementById("cuotas");
const parcela = document.getElementById("id_parcela");
const precioTotalTerreno = document.getElementById("precio_total_terreno");
const precioTotalEntrega = document.getElementById("precio_total_entrega");
const promedioCemento = document.getElementById("promedio_cemento");
const fechaDesde = document.getElementById("fecha_desde");
const fechaHasta = document.getElementById("fecha_hasta");
const cuotaMensualBolsasCemento = document.getElementById(
    "cuota_mensual_bolsas_cemento"
);
const valorCuota = document.getElementById("valor_cuota");
const valorTotalFinanciar = document.getElementById("valorTotalFinanciar");
const btnGuardarVenta = document.getElementById("btnGuardarVenta");
const alertaVenta = document.getElementById("alertaVenta");

document.addEventListener("DOMContentLoaded", async () => {
    parcela.addEventListener("change", function (e) {
        const value = this.options[this.selectedIndex].value;
        formData["idParcela"] = value;
        calcularPlanVenta();
    });

    cuotas.addEventListener("keyup", function (e) {
        formData["cantidadCuotas"] = e.target.value;
        calcularPlanVenta();
    });

    precioTotalEntrega.addEventListener("keyup", function (e) {
        formData["precioTotalEntrega"] = e.target.value;
        calcularPlanVenta();
    });
    promedioCemento.addEventListener("keyup", function (e) {
        formData["promedioCemento"] = e.target.value;
        calcularPlanVenta();
    });

    const calcularPlanVenta = async () => {
        if (
            !formData.idParcela ||
            !formData.cantidadCuotas ||
            // !formData.precioTotalEntrega ||
            !formData.promedioCemento
        ) {
            return;
        }

        const result = await fetchHelper(
            "/ventas/calcularPlan",
            formData,
            "POST"
        );

        if ("mensaje" in result) {
            mensaje(result.mensaje);
            return;
        }

        if (formData.precioTotalEntrega > result.precioTotalTerreno) {
            mensaje(
                "El total de la entrega no puede ser mayor al precio total del terreno"
            );
            return;
        }

        mensaje();
        fechaDesde.value = result.fechaDesde;
        fechaHasta.value = result.fechaHasta;
        cuotaMensualBolsasCemento.value = result.cuotaMensualBolsasCemento;
        valorCuota.value = result.valorCuota;
        valorTotalFinanciar.value = result.valorTotalFinanciar;
        precioTotalTerreno.value = result.precioTotalTerreno;
    };

    const mensaje = (textoMensaje) => {
        if (textoMensaje) {
            alertaVenta.style.display = "block";
            alertaVenta.innerHTML = "";
            alertaVenta.innerHTML = `
            <div class="alert alert-danger alert-dismissible fade show mt-2">
                <strong>${textoMensaje}</strong>
            </div>`;
            btnGuardarVenta.disabled = true;
            return;
        }
        alertaVenta.innerHTML = "";
        alertaVenta.style.display = "none";
        btnGuardarVenta.disabled = false;
    };
    // const cargarDatosForm = () => {
    //     formData["idParcela"] = parcela.value;
    //     formData["cantidadCuotas"] = cuotas.value;
    //     formData["precioTotalEntrega"] = precioTotalEntrega.value;
    //     formData["promedioCemento"] = promedioCemento.value;
    // };
});
