-- Contador unico de numeros de recibo (compartido entre cuotas y comprobantes).
-- DetalleVenta::getSiguienteNumeroRecibo() lo bloquea con SELECT ... FOR UPDATE
-- para que dos cobros simultaneos no obtengan el mismo numero.
--
-- EJECUTAR DESPUES de fix-recibos-duplicados-10000.sql (igual el codigo toma el
-- maximo real si el contador quedara por detras).

CREATE TABLE IF NOT EXISTS numeracion_recibos (
    id TINYINT UNSIGNED NOT NULL PRIMARY KEY,
    ultimo BIGINT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO numeracion_recibos (id, ultimo)
SELECT 1, GREATEST(
    COALESCE((SELECT MAX(CAST(numero_recibo AS UNSIGNED)) FROM detalle_ventas), 0),
    COALESCE((SELECT MAX(CAST(numero_recibo AS UNSIGNED)) FROM comprobantes), 0)
)
ON DUPLICATE KEY UPDATE ultimo = GREATEST(ultimo, VALUES(ultimo));

-- Los volantes buscan por numero_recibo
CREATE INDEX detalle_ventas_numero_recibo_index ON detalle_ventas (numero_recibo(20));
CREATE INDEX comprobantes_numero_recibo_index ON comprobantes (numero_recibo(20));
