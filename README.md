hoyos
=====

Aplicación web con interfaz móvil que los ciudadanos puedan utilizar para reportar los hoyos en la carretera con sus coordenadas de ubicación, asistiendo y agilizando el proceso de reparación por las agencias pertinentes.

# Inspiración

Luego de visitar a un cliente en una cita de negocios que tomo varias horas, salgo muy hambriento a comer en un Taco Maker de Carolina. Ya que resido en el área este, decido viajar a mi residencia tomando la ruta de Canovanas a Juncos (mejor conocida como "las cuatrocientas") debido a la congestión de tránsito de San Juan a Caguas.

Tomo una carretera que me lleva a la autopista en dirección a Canóvanas cuando desafortunadamente mi vehículo se estremece debido a un hoyo en la zona de Carolina. Acabado de comer, me bajo muy frustrado y molesto (como ser humano) a cambiar la goma del vehículo que se vació al instante, notando un movimiento raro en el "struct".

Al otro día llevo mi auto a cambiar la goma para descubrir que también las suspensiones se dañaron (lo que temía). En fin la reparación costo $630 dolares, y no es la primera vez que me pasa. Así como yo imagino que otros ciudadanos han pasado por lo mismo.

# Detalles Técnicos

La aplicación hará uso de las nuevas tecnologías proveídas por el estándar de HTML5 además de librerías JavaScript para su visualización y el lenguaje PHP para el procesamiento de datos.

Cuando el ciudadano abra la aplicación la misma detectará si su dispositivo soporta el API de "GeoLocation", de soportarlo utilizará las coordenadas reportadas por el mismo para el reporte del hoyo, de no soportarlo el ciudadano podrá entrar la dirección donde se ubica el hoyo así como compartir foto del mismo de manera opcional.

Otros ciudadanos que reporten un hoyo en las cercanías podrán escoger de un listado de hoyos pre-existentes para votar por ellos en vez de duplicar el reporte.

El Departamento de Transportación y Obras Públicas (DTOP) podrá ver un listado de hoyos recientes además de tener la opción de filtrarlos por pueblo, hoyos más votados y subscribirse a notificaciones por correo electrónico de nuevos hoyos por pueblo. Además DTOP podrá modificar el estatus del hoyo a reparado.

## Herramientas a utilizar

* Lenguaje PHP - Procesamiento de datos.
* JqueryMobile - Intefaz gráfica.
* HTML5 Geolocation API - Obtención de coordenadas.
* SQLite - Base de datos para almacenar los reportes.
* Google Maps - Visualización opcional de los hoyos en el mapa de Puerto Rico.

# Plan de desarrollo

## A corto plazo

* Crear interfaz de reporte de hoyos.
* Crear interfaz para listar hoyos reportados.
* Programar sistema de recolección de datos usando PHP.
* La aplicación debería ser accesible por web se podrán reportar hoyos y verlos por pueblo.
* Vista de hoyos utilizando la API de Google Maps.

## A largo plazo

* Sistema de notificaciones por correo electrónico de reportes de nuevos hoyos.
* Estadísticas de una zona donde los hoyos se vuelven a reabrir para ayudar a tomar una decisión de reparación adecuada.
* Integración con PhoneGap para una experiencia más nativa.
* Sistema de "Tracking" para los ciudadanos. Detección de su ubicación actual que brinda un listados de hoyos en las cercanías, así como notificaciones en voz.
* Sistema administrativo para modificar el estatus de un hoyo.
