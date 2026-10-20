-- Correccion del numero de recibo duplicado "10000"
--
-- Causa: getSiguienteNumeroRecibo() ordenaba numero_recibo (varchar) como texto,
-- por lo que el "maximo" era "9999" y se generaba siempre el 10000.
--
-- Criterio para reconstruir los pagos:
--   * Pago individual (FormCobrarCuotas)  -> total_intereses IS NOT NULL -> 1 recibo por cuota
--   * Pago multiple   (FormCobrarTodo)    -> total_intereses IS NULL     -> 1 recibo por (venta, fecha_pago)
--   * Comprobantes con 10000              -> 1 recibo por comprobante
--
-- Los numeros nuevos continuan desde el maximo actual (detalle_ventas + comprobantes),
-- ordenados cronologicamente.
--
-- USO: ejecutar sobre la base elegida (mysql -u root NOMBRE_BASE < este_archivo.sql).
--      Es idempotente: si ya no queda ningun recibo 10000 no modifica nada.
--      Rollback: UPDATE ... JOIN con las tablas bak_recibo10000_* (ver final del archivo).

-- 1) Backup (fuera de la transaccion porque CREATE TABLE hace commit implicito)
CREATE TABLE IF NOT EXISTS bak_recibo10000_detalle_ventas AS
    SELECT id_detalle_venta, numero_recibo FROM detalle_ventas WHERE numero_recibo = '10000';
CREATE TABLE IF NOT EXISTS bak_recibo10000_comprobantes AS
    SELECT id_comprobante, numero_recibo FROM comprobantes WHERE numero_recibo = '10000';

START TRANSACTION;

SET @base := CAST(GREATEST(
    COALESCE((SELECT MAX(CAST(numero_recibo AS UNSIGNED)) FROM detalle_ventas), 0),
    COALESCE((SELECT MAX(CAST(numero_recibo AS UNSIGNED)) FROM comprobantes), 0)
) AS UNSIGNED);

-- 2) Cuota -> clave de pago
DROP TEMPORARY TABLE IF EXISTS map_cuota;
CREATE TEMPORARY TABLE map_cuota (
    id_detalle_venta BIGINT UNSIGNED PRIMARY KEY,
    clave VARCHAR(100) NOT NULL
);
INSERT INTO map_cuota
SELECT id_detalle_venta,
       IF(total_intereses IS NULL,
          CONCAT('M-', id_venta, '-', fecha_pago),
          CONCAT('D-', id_detalle_venta))
FROM detalle_ventas
WHERE numero_recibo = '10000';

-- 3) Unidades de recibo (una por pago real), en orden cronologico
DROP TEMPORARY TABLE IF EXISTS unidades;
CREATE TEMPORARY TABLE unidades (
    seq INT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(100) NOT NULL,
    fecha VARCHAR(20),
    id_orden BIGINT UNSIGNED,
    UNIQUE KEY (clave)
);
INSERT INTO unidades (clave, fecha, id_orden)
SELECT clave, fecha, id_orden FROM (
    SELECT m.clave, MIN(d.fecha_pago) AS fecha, MIN(d.id_detalle_venta) AS id_orden, 1 AS tipo
    FROM map_cuota m JOIN detalle_ventas d USING (id_detalle_venta)
    GROUP BY m.clave
    UNION ALL
    SELECT CONCAT('C-', id_comprobante), fecha_comprobante, id_comprobante, 2
    FROM comprobantes WHERE numero_recibo = '10000'
) t
ORDER BY fecha, tipo, id_orden;

-- 4) Aplicar
UPDATE detalle_ventas d
JOIN map_cuota m USING (id_detalle_venta)
JOIN unidades u ON u.clave = m.clave
SET d.numero_recibo = CAST(CAST(@base + u.seq AS UNSIGNED) AS CHAR);

UPDATE comprobantes c
JOIN unidades u ON u.clave = CONCAT('C-', c.id_comprobante)
SET c.numero_recibo = CAST(CAST(@base + u.seq AS UNSIGNED) AS CHAR);

-- 5) Verificacion (debe dar 0 filas / 0 en todo)
SELECT 'quedan con 10000 (detalle_ventas)' AS control, COUNT(*) AS valor FROM detalle_ventas WHERE numero_recibo = '10000'
UNION ALL
SELECT 'quedan con 10000 (comprobantes)', COUNT(*) FROM comprobantes WHERE numero_recibo = '10000'
UNION ALL
SELECT 'recibos repetidos entre ventas distintas', COUNT(*) FROM (
    SELECT numero_recibo FROM detalle_ventas WHERE numero_recibo IS NOT NULL
    GROUP BY numero_recibo HAVING COUNT(DISTINCT id_venta) > 1) t
UNION ALL
SELECT 'recibo individual con >1 cuota', COUNT(*) FROM (
    SELECT numero_recibo FROM detalle_ventas WHERE numero_recibo IS NOT NULL
    GROUP BY numero_recibo HAVING COUNT(*) > 1 AND SUM(total_intereses IS NOT NULL) > 0) t
UNION ALL
SELECT 'recibo en detalle_ventas y comprobantes a la vez', COUNT(*) FROM (
    SELECT d.numero_recibo FROM detalle_ventas d JOIN comprobantes c ON c.numero_recibo = d.numero_recibo
    GROUP BY d.numero_recibo) t;

COMMIT;

-- Rollback manual (si hiciera falta):
-- UPDATE detalle_ventas d JOIN bak_recibo10000_detalle_ventas b USING (id_detalle_venta) SET d.numero_recibo = b.numero_recibo;
-- UPDATE comprobantes c JOIN bak_recibo10000_comprobantes b USING (id_comprobante) SET c.numero_recibo = b.numero_recibo;
