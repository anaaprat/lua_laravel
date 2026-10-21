# LUA — Sistema de gestión para bares

## Descripción

LUA es un sistema de gestión de pedidos para establecimientos de hostelería, desarrollado como proyecto final del Ciclo Formativo de Grado Superior en Desarrollo de Aplicaciones Multiplataforma (DAM).

El proyecto nace de la necesidad de mejorar la experiencia de los clientes y optimizar el trabajo de los bares, especialmente en momentos de gran afluencia. La idea surgió a partir de los establecimientos situados en la calle Lua de San Fernando (Cádiz).

LUA está compuesto por una plataforma web, una API REST y una aplicación móvil desarrollada con Flutter.

**Este repositorio contiene el backend y la plataforma web desarrollados con Laravel.** La aplicación móvil forma parte del proyecto global, pero su código no está incluido aquí.

## Funcionalidades principales

### Panel web para establecimientos

- Gestión de productos, precios, disponibilidad y stock.
- Recepción y seguimiento de pedidos.
- Gestión de mesas.
- Recarga de crédito para clientes.
- Consulta del historial de pedidos y movimientos.
- Estadísticas de ventas y productos más demandados.
- Generación de códigos QR identificativos.

### Panel de administración

- Gestión de usuarios y establecimientos.
- Administración del catálogo general de productos.
- Gestión de pedidos y movimientos de crédito.
- Supervisión de rankings.
- Activación y desactivación de cuentas.
- Consulta de información general del sistema.

### Backend y API REST

La API permite la comunicación entre la aplicación móvil y la base de datos, incluyendo:

- Autenticación y gestión de usuarios.
- Validación de códigos QR.
- Consulta de establecimientos y productos.
- Gestión de pedidos.
- Consulta y actualización del crédito.
- Historial de movimientos.
- Gestión de rankings entre usuarios.

## Tecnologías utilizadas

| Tecnología | Uso |
|---|---|
| PHP | Desarrollo del backend |
| Laravel 10 | Framework principal |
| MySQL | Base de datos relacional |
| Blade | Plantillas de la plataforma web |
| Tailwind CSS | Diseño de interfaces |
| JavaScript | Funcionalidades interactivas |
| API REST | Comunicación con la aplicación móvil |
| Git y GitHub | Control de versiones |
| Postman | Pruebas de la API |

## Estructura del sistema

El sistema diferencia tres tipos de usuarios:

**Clientes:** utilizan la aplicación móvil para consultar las cartas digitales, realizar pedidos, gestionar su crédito y participar en rankings.

**Establecimientos:** utilizan el panel web para administrar sus productos, gestionar pedidos, consultar estadísticas y realizar recargas de crédito.

**Administradores:** supervisan los usuarios, establecimientos, productos, pedidos y movimientos del sistema.

## Base de datos

El proyecto utiliza MySQL como sistema de gestión de bases de datos relacionales.

La estructura incluye entidades para usuarios, productos, productos asociados a establecimientos, pedidos, detalles de pedidos, movimientos de crédito y rankings.

Las relaciones y la estructura de las tablas se gestionan mediante modelos y migraciones de Laravel.

## Seguridad

El sistema incorpora autenticación, gestión de roles y protección de rutas según el tipo de usuario.

Las credenciales y configuraciones específicas de cada entorno se almacenan en archivos privados que no se incluyen en el repositorio.

## Posibles mejoras futuras

- Incorporación de notificaciones sobre el estado de los pedidos.
- Integración de nuevos métodos de pago.
- Sistema de reservas de mesas.
- Ampliación de estadísticas e informes.
- Implementación de programas de fidelización.
- Adaptación del sistema a otros sectores y establecimientos.

## Documentación

El desarrollo completo del proyecto, las decisiones técnicas, las pruebas realizadas y los manuales de usuario se encuentran recogidos en la memoria del proyecto final.

## Autora

**Ana Prat Garrido**

Proyecto Integrado — Desarrollo de Aplicaciones Multiplataforma (DAM)

Colegio Salesianos «San Ignacio», Cádiz — 2025.
