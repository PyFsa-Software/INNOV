import { fetchHelper } from "../utils/fetch";

const formData = {
    idCliente: 0,
    idParcela: 0,
    cantidadCuotas: 0,
};

document.addEventListener("DOMContentLoaded", async () => {
    document
        .getElementById("id_cliente")
        .addEventListener("change", function (e) {
            console.log(e.target.name);
            const value = this.options[this.selectedIndex].value;
            console.log(value);
            formData["idCliente"] = value;
            calcularPlanVenta();
        });

    document
        .getElementById("id_parcela")
        .addEventListener("change", function (e) {
            console.log(e.target.name);
            const value = this.options[this.selectedIndex].value;
            console.log(value);
            formData["idParcela"] = value;
            calcularPlanVenta();
        });

    document.getElementById("cuotas").addEventListener("keyup", function (e) {
        console.log(e.target.value);
        formData["cantidadCuotas"] = e.target.value;
        calcularPlanVenta();
    });

    const calcularPlanVenta = async () => {
        if (
            formData.idCliente === 0 ||
            formData.idParcela === 0 ||
            formData.cantidadCuotas === 0
        ) {
            return;
        }

        const result = await fetchHelper(
            "/ventas/calcularPlan",
            formData,
            "POST"
        );

        console.log(result);
    };
});
