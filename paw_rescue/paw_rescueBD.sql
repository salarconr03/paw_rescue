-- ================================================================
-- proyecto: paw_rescue (PostgreSQL)
-- ================================================================
GRANT USAGE, CREATE ON SCHEMA paw_rescue TO murasaki; para dar permisos desde superuser postgresql


CREATE SCHEMA IF NOT EXISTS paw_rescue; //esquemas
SET search_path TO paw_rescue;


-- ================================================================
-- 1. catálogos
-- ================================================================

CREATE TABLE paw_rescue.tipo_id (
    id_tipo INT PRIMARY KEY,
    nombre VARCHAR(50)
);

CREATE TABLE paw_rescue.especie (
    id_esp INT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

CREATE TABLE paw_rescue.raza (
    id_raza INT PRIMARY KEY,
    nombre VARCHAR(100),
    id_esp INT REFERENCES paw_rescue.especie(id_esp)
);

CREATE TABLE paw_rescue.tam (
    id_tam INT PRIMARY KEY,
    nombre VARCHAR(50)
);

CREATE TABLE paw_rescue.temperamento (
    id_temp INT PRIMARY KEY,
    nombre VARCHAR(50)
);

CREATE TABLE paw_rescue.color (
    id_color INT PRIMARY KEY,
    nombre VARCHAR(50)
);

CREATE TABLE paw_rescue.color_ojos (
    id_ojos INT PRIMARY KEY,
    nombre VARCHAR(50)
);

CREATE TABLE paw_rescue.estatus_adop (
    id_estatus INT PRIMARY KEY,
    nombre VARCHAR(50)
);

CREATE TABLE paw_rescue.estado_animal (
    id_estado INT PRIMARY KEY,
    nombre VARCHAR(50)
);

-- ================================================================
-- 2. usuarios y roles
-- ================================================================

CREATE TABLE paw_rescue.usuario (
    id_usuario INT PRIMARY KEY,
    nombre VARCHAR(150),
    correo VARCHAR(150) UNIQUE
);

CREATE TABLE paw_rescue.rol (
    id_rol INT PRIMARY KEY,
    nombre VARCHAR(50)
);

CREATE TABLE paw_rescue.usuario_rol (
    id_usuario INT REFERENCES paw_rescue.usuario(id_usuario),
    id_rol INT REFERENCES paw_rescue.rol(id_rol),
    PRIMARY KEY (id_usuario, id_rol)
);

CREATE TABLE paw_rescue.adoptante (
    id_usuario INT PRIMARY KEY REFERENCES paw_rescue.usuario(id_usuario)
);

-- ================================================================
-- 3. refugios
-- ================================================================

CREATE TABLE paw_rescue.refugio (
    id_ref INT PRIMARY KEY,
    nombre VARCHAR(100),
    direccion VARCHAR(255),
    telefono VARCHAR(20)
);

-- ================================================================
-- 4. animal
-- ================================================================

CREATE TABLE paw_rescue.animal (
    id_animal INT PRIMARY KEY,
    nombre VARCHAR(120),
    id_esp INT REFERENCES paw_rescue.especie(id_esp),
    id_raza INT REFERENCES paw_rescue.raza(id_raza),
    id_tam INT REFERENCES paw_rescue.tam(id_tam),
    id_color INT REFERENCES paw_rescue.color(id_color),
    id_ojos INT REFERENCES paw_rescue.color_ojos(id_ojos),
    id_temp INT REFERENCES paw_rescue.temperamento(id_temp),
    id_estatus INT REFERENCES paw_rescue.estatus_adop(id_estatus),
    id_estado INT REFERENCES paw_rescue.estado_animal(id_estado),
    id_ref INT REFERENCES paw_rescue.refugio(id_ref),
    edad_aprox SMALLINT,
    fecha_reg TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ================================================================
-- 5. identificación
-- ================================================================

CREATE TABLE paw_rescue.ident_animal (
    id_ident SERIAL PRIMARY KEY,
    id_animal INT REFERENCES paw_rescue.animal(id_animal),
    id_tipo INT REFERENCES paw_rescue.tipo_id(id_tipo),
    codigo VARCHAR(100) DEFAULT 'sin_asignar',
    tiene_id BOOLEAN DEFAULT FALSE,
    fecha DATE
);

-- ================================================================
-- 6. rescate y eventos
-- ================================================================

CREATE TABLE paw_rescue.rescate (
    id_rescate SERIAL PRIMARY KEY,
    id_animal INT REFERENCES paw_rescue.animal(id_animal),
    fecha DATE,
    lugar VARCHAR(255),
    id_usuario INT REFERENCES paw_rescue.usuario(id_usuario),
    condiciones VARCHAR(1000)
);

CREATE TABLE paw_rescue.evento_animal (
    id_evento SERIAL PRIMARY KEY,
    id_animal INT REFERENCES paw_rescue.animal(id_animal),
    tipo VARCHAR(50),
    descripcion VARCHAR(1000),
    fecha DATE
);

CREATE TABLE paw_rescue.hist_estado (
    id_hist SERIAL PRIMARY KEY,
    id_animal INT REFERENCES paw_rescue.animal(id_animal),
    id_estado INT REFERENCES paw_rescue.estado_animal(id_estado),
    fecha DATE,
    obs VARCHAR(1000)
);

-- ================================================================
-- 7. salud
-- ================================================================

CREATE TABLE paw_rescue.enfermedad (
    id_enf INT PRIMARY KEY,
    nombre VARCHAR(150)
);

CREATE TABLE paw_rescue.vacuna (
    id_vac INT PRIMARY KEY,
    nombre VARCHAR(150),
    esencial BOOLEAN
);

CREATE TABLE paw_rescue.via_admin (
    id_via INT PRIMARY KEY,
    nombre VARCHAR(50)
);

CREATE TABLE paw_rescue.vacuna_enf (
    id_vac INT REFERENCES paw_rescue.vacuna(id_vac),
    id_enf INT REFERENCES paw_rescue.enfermedad(id_enf),
    PRIMARY KEY (id_vac, id_enf)
);

CREATE TABLE paw_rescue.enf_animal (
    id_animal INT REFERENCES paw_rescue.animal(id_animal),
    id_enf INT REFERENCES paw_rescue.enfermedad(id_enf),
    fecha DATE,
    PRIMARY KEY (id_animal, id_enf)
);

CREATE TABLE paw_rescue.hist_vac (
    id_hist SERIAL PRIMARY KEY,
    id_animal INT REFERENCES paw_rescue.animal(id_animal),
    id_vac INT REFERENCES paw_rescue.vacuna(id_vac),
    id_via INT REFERENCES paw_rescue.via_admin(id_via),
    fecha_ap DATE,
    fecha_exp DATE,
    lote VARCHAR(50),
    vet VARCHAR(100),
    obs VARCHAR(1000)
);

CREATE TABLE paw_rescue.desparasitacion (
    id_des SERIAL PRIMARY KEY,
    id_animal INT REFERENCES paw_rescue.animal(id_animal),
    tipo VARCHAR(20),
    producto VARCHAR(100),
    fecha DATE,
    proxima DATE,
    peso DECIMAL(5,2)
);

CREATE TABLE paw_rescue.salud_actual (
    id_salud SERIAL PRIMARY KEY,
    id_animal INT REFERENCES paw_rescue.animal(id_animal),
    enfermo BOOLEAN DEFAULT TRUE,
    diagnostico VARCHAR(1000),
    obs VARCHAR(1000),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ================================================================
-- 8. consultas
-- ================================================================

CREATE TABLE paw_rescue.consulta (
    id_cons SERIAL PRIMARY KEY,
    id_animal INT REFERENCES paw_rescue.animal(id_animal),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    peso DECIMAL(5,2),
    temp DECIMAL(4,2),
    fc INT,
    fr INT,
    diagnostico VARCHAR(1500),
    plan VARCHAR(1500)
);

CREATE TABLE paw_rescue.tratamiento (
    id_trat SERIAL PRIMARY KEY,
    id_cons INT REFERENCES paw_rescue.consulta(id_cons),
    medicamento VARCHAR(100),
    dosis VARCHAR(50),
    frecuencia INT,
    duracion INT,
    indicaciones VARCHAR(1000)
);

CREATE TABLE paw_rescue.peso_hist (
    id_peso SERIAL PRIMARY KEY,
    id_animal INT REFERENCES paw_rescue.animal(id_animal),
    peso DECIMAL(5,2),
    fecha DATE,
    obs VARCHAR(500)
);

-- ================================================================
-- 9. adopciones
-- ================================================================

CREATE TABLE paw_rescue.adopcion (
    id_animal INT REFERENCES paw_rescue.animal(id_animal),
    id_usuario INT REFERENCES paw_rescue.adoptante(id_usuario),
    fecha DATE,
    PRIMARY KEY (id_animal, id_usuario)
);

CREATE TABLE paw_rescue.seg_adop (
    id_seg SERIAL PRIMARY KEY,
    id_animal INT REFERENCES paw_rescue.animal(id_animal),
    id_usuario INT REFERENCES paw_rescue.usuario(id_usuario),
    fecha DATE,
    obs VARCHAR(1000),
    estado VARCHAR(50)
);

-- ================================================================
-- 10. imágenes
-- ================================================================

CREATE TABLE paw_rescue.img_especie (
    id_img SERIAL PRIMARY KEY,
    id_esp INT REFERENCES paw_rescue.especie(id_esp),
    url VARCHAR(255),
    descripcion VARCHAR(150)
);

CREATE TABLE paw_rescue.img_raza (
    id_img SERIAL PRIMARY KEY,
    id_raza INT REFERENCES paw_rescue.raza(id_raza),
    url VARCHAR(255),
    descripcion VARCHAR(150)
);

CREATE TABLE paw_rescue.img_animal_principal (
    id_img SERIAL PRIMARY KEY,
    id_animal INT UNIQUE REFERENCES paw_rescue.animal(id_animal),
    url VARCHAR(255),
    descripcion VARCHAR(150),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE paw_rescue.img_animal_galeria (
    id_img SERIAL PRIMARY KEY,
    id_animal INT REFERENCES paw_rescue.animal(id_animal),
    url VARCHAR(255),
    descripcion VARCHAR(150),
    destacada BOOLEAN DEFAULT FALSE,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
