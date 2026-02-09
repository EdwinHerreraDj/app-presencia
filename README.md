# Sistema de Control de Presencia con Geolocalización

Aplicación web desarrollada para el **control de presencia de empleados**, orientada a empresas que necesitan **registrar entradas y salidas**, verificar la **ubicación física del fichaje** y obtener **resúmenes de horas trabajadas**.

El sistema está diseñado como **app web** (no app móvil), usando geolocalización del navegador y validaciones de seguridad en backend.

---

## 🧠 Objetivo del proyecto

Proporcionar un sistema fiable para:

- Registrar fichajes de entrada y salida
- Validar que el fichaje se realiza dentro de un **radio geográfico permitido**
- Controlar incidencias (olvidos de fichaje)
- Calcular horas trabajadas por empleado
- Facilitar inspecciones y control interno

Todo con una arquitectura clara y preparada para ampliaciones futuras.

---

## 🛠️ Stack tecnológico

### Backend
- **Laravel**
- PHP 8+
- MySQL

### Frontend
- Blade
- Tailwind CSS
- JavaScript (Geolocation API)

### Otros
- Livewire (componentes interactivos)
- Git / GitHub

---

## 📦 Funcionalidades principales

### Fichaje con geolocalización
- Registro de **entrada** y **salida**
- Uso de la API de geolocalización del navegador
- Validación por:
  - latitud
  - longitud
  - radio permitido
- Marcado automático de fichajes:
  - dentro de rango
  - fuera de rango

---

### Gestión de empleados
- Separación clara entre:
  - **usuarios del sistema**
  - **empleados fichables**
- Los empleados no usan email para fichar
- Asociación a empresa y ubicación

---

### Ubicaciones de empresa
- Configuración de:
  - latitud
  - longitud
  - radio de fichaje
- Ubicación centralizada por empresa
- Actualizable sin afectar a empleados individualmente

---

### Incidencias
- Los empleados pueden registrar incidencias:
  - olvido de fichaje
- Las incidencias quedan pendientes de revisión
- Visualización agrupada por empresa y empleado

---

### Resumen de horas
- Cálculo automático de:
  - horas trabajadas
  - tiempo total por periodo
- Preparado para exportación y control administrativo

---

## 🔐 Arquitectura y criterios técnicos

- Validaciones de geolocalización en backend
- Separación clara de responsabilidades:
  - fichajes
  - empleados
  - ubicaciones
- Evita duplicidad de datos
- Pensado para uso real en empresas, no como demo

---

## 🚀 Instalación básica

```bash
composer install
npm install
npm run build
php artisan migrate
php artisan serve
