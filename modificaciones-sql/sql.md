## Reservas

```sql
ALTER TABLE ventas DROP importe_entrega, DROP forma_pago, DROP concepto_de;

CREATE TABLE reserva_parcela ( id_reserva_parcela INT PRIMARY KEY AUTO_INCREMENT, id_cliente INT(255), id_parcela INT(255), fecha_reserva DATE, monto_total VARCHAR(255),estado_reserva BOOLEAN NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);

CREATE TABLE detalle_reserva_parcela ( id_detalle_reserva_parcela INT PRIMARY KEY AUTO_INCREMENT, id_reserva_parcela INT, fecha_pago VARCHAR(255), forma_pago ENUM('EFECTIVO', 'TRANSFERENCIA', 'DEBITO'), concepto_de VARCHAR(255), importe_pago VARCHAR(255), cancelado BOOLEAN NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, FOREIGN KEY (id_reserva_parcela) REFERENCES reserva_parcela (id_reserva_parcela));
```

## Monedas de Pagos

```sql
ALTER TABLE detalle_ventas MODIFY COLUMN moneda_pago VARCHAR(255);
ALTER TABLE detalle_reserva_parcela MODIFY COLUMN moneda_pago VARCHAR(255);
```

## Tabla comprobantes

```sql
CREATE TABLE `comprobantes` (
  `id_comprobante` bigint unsigned NOT NULL AUTO_INCREMENT,
  `descripcion_comprobante` varchar(255) NOT NULL,
  `numero_recibo` varchar(255) NOT NULL,
  `fecha_comprobante` date NOT NULL,
  `forma_pago` varchar(255) NOT NULL,
  `importe_total` varchar(255) NOT NULL,
  `moneda_pago` varchar(255) NOT NULL,
  `concepto_de` varchar(255) NOT NULL,
  `sr_sra` varchar(255) DEFAULT NULL,
  `dni` varchar(255) DEFAULT NULL,
  `domicilio` varchar(255) DEFAULT NULL,
  `domicilio_alquiler` varchar(255) DEFAULT NULL,
  `id_cliente` bigint unsigned DEFAULT NULL,
  `id_venta` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_comprobante`),
  FOREIGN KEY (`id_cliente`) REFERENCES `personas`(`id_persona`),
  FOREIGN KEY (`id_venta`) REFERENCES `ventas`(`id_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## Se aplico la siguiente query UPDATE para actualizar todas las fechas maximas a pagar al dia 15 de las cuotas asociadas al lote TIERRA VERDE.

```sql
UPDATE detalle_ventas d1 INNER JOIN ventas v1 ON d1.id_venta = v1.id_venta INNER JOIN parcelas p1 ON p1.id_parcela = v1.id_parcela INNER JOIN lotes l1 ON p1.id_lote = l1.id_lote INNER JOIN personas pe1 ON pe1.id_persona = v1.id_cliente SET d1.fecha_maxima_a_pagar = DATE_FORMAT(d1.fecha_maxima_a_pagar, '%Y-%m-15') WHERE l1.nombre_lote = 'TIERRA VERDE' AND d1.pagado = 'no';
```

## Se aplico la siguiente query UPDATE para actualizar todas las fechas maximas a pagar al dia 15 a todas las cuotas.

```sql
UPDATE detalle_ventas d1 INNER JOIN ventas v1 ON d1.id_venta = v1.id_venta INNER JOIN parcelas p1 ON p1.id_parcela = v1.id_parcela INNER JOIN lotes l1 ON p1.id_lote = l1.id_lote INNER JOIN personas pe1 ON pe1.id_persona = v1.id_cliente SET d1.fecha_maxima_a_pagar = DATE_FORMAT(d1.fecha_maxima_a_pagar, '%Y-%m-15') WHERE d1.pagado = 'no';
```

## Se agrego el campo leyenda a la tabla detalle_ventas

```sql
ALTER TABLE `detalle_ventas` ADD `leyenda` VARCHAR(255) NULL AFTER `moneda_pago`;
```

## Se agrego el campo leyenda a la tabla detalle_reserva_parcela

```sql
ALTER TABLE `detalle_reserva_parcela` ADD `leyenda` VARCHAR(255) NULL AFTER `moneda_pago`;
```

## Se agrego el update_period a la tabla ventas

```sql
ALTER TABLE ventas
ADD COLUMN update_period ENUM('BIMESTRAL', 'TRIMESTRAL', 'SEMESTRAL') NOT NULL DEFAULT 'SEMESTRAL';

```
