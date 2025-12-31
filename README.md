# Sistema de Préstamos – Fondo de Asociados

Sistema web desarrollado en **Laravel** para la gestión de un **fondo común de asociados**, donde varios socios aportan capital, se realizan préstamos a terceros y las utilidades se distribuyen proporcionalmente según el aporte de cada asociado.

El sistema está diseñado con una arquitectura sólida, escalable y orientada a buenas prácticas de desarrollo backend.

---

## Funcionalidad principal

- Gestión de usuarios con **roles** (admin / asociado)
- Autenticación segura (login / logout)
- Gestión de asociados (socios inversionistas)
- Gestión de clientes (personas o empresas)
- Gestión de préstamos:
  - Aprobación
  - Desembolso
  - Estados del préstamo
- Generación de cuotas por préstamo
- Registro de pagos
- Asignación de pagos a cuotas
- Control de caja (ledger financiero)
- Cálculo de beneficios por periodo
- Distribución de utilidades a asociados
- Registro de acciones de cobranza
- Sistema de notificaciones
- Sistema de backups
- Control de intentos de login (Rate Limiting)

---

## Arquitectura y diseño

- **Backend:** Laravel 12
- **Base de datos:** MySQL
- **Autenticación:** Laravel Breeze (Blade)
- **ORM:** Eloquent
- **Patrón:** MVC + Services (en evolución)
- **Control financiero:** Ledger contable (tabla `caja`)
- **Idioma de columnas:** Español (decisión consciente de dominio)

---

## Roles del sistema

### Administrador
- Acceso total al sistema
- Aprobación de préstamos
- Registro de pagos
- Cálculo de beneficios
- Distribución de utilidades
- Gestión completa de datos

### Asociado
- Usuario que aporta capital al fondo
- Consulta de información financiera
- Recepción de utilidades
- Acceso restringido (solo lectura en la mayoría de módulos)

> Nota: Un usuario puede ser **admin**, **asociado**, o ambos.

---

## Estructura de base de datos (resumen)

Algunas tablas clave:

- `users` – Usuarios del sistema
- `roles` – Roles (admin / asociado)
- `asociados` – Perfil financiero de socios
- `clientes` – Clientes que reciben préstamos
- `prestamos` – Préstamos otorgados
- `cuotas` – Plan de pagos
- `pagos` – Pagos recibidos
- `asignacion_pagos` – Distribución del pago a cuotas
- `caja` – Ledger financiero (entradas / salidas)
- `periodo_beneficio` – Cálculo de utilidades por periodo
- `distribucion` – Reparto de beneficios
- `acciones` – Acciones de cobranza
- `documentos` – Soportes y archivos
- `notificaciones` – Notificaciones del sistema
- `backups` – Registro de respaldos

---

## Instalación local

### Requisitos
- PHP >= 8.2
- Composer
- Node.js >= 20
- MySQL
- Git


