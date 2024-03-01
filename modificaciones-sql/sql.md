## Reservas

```sql
ALTER TABLE ventas DROP importe_entrega, DROP forma_pago, DROP concepto_de;

CREATE TABLE reserva_parcela ( id_reserva_parcela INT PRIMARY KEY AUTO_INCREMENT, id_cliente INT(255), id_parcela INT(255), fecha_reserva DATE, monto_total VARCHAR(255),estado_reserva BOOLEAN NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);

CREATE TABLE detalle_reserva_parcela ( id_detalle_reserva_parcela INT PRIMARY KEY AUTO_INCREMENT, id_reserva_parcela INT, fecha_pago VARCHAR(255), forma_pago ENUM('EFECTIVO', 'TRANSFERENCIA', 'DEBITO'), concepto_de VARCHAR(255), importe_pago VARCHAR(255), cancelado BOOLEAN NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, FOREIGN KEY (id_reserva_parcela) REFERENCES reserva_parcela (id_reserva_parcela));
```

## Monedas de Pagos

```sql
ALTER TABLE detalle_ventas ADD COLUMN moneda_pago ENUM('DOLARES', 'PESO') NULL;
ALTER TABLE detalle_reserva_parcela ADD COLUMN moneda_pago ENUM('DOLARES', 'PESO') NULL;
```