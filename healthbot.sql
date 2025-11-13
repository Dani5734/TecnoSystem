-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-11-2025 a las 06:59:33
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `healthbot`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `id_administrador` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `telefono` double NOT NULL,
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`id_administrador`, `nombre`, `apellidos`, `correo`, `telefono`, `contrasena`) VALUES
(1, 'esteban', 'Martinez Flores', 'estebanflores20@tecnosystem.com', 5578293928, 'Admin.123');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planes`
--

CREATE TABLE `planes` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contenido` varchar(800) NOT NULL,
  `estatura` double NOT NULL,
  `peso` double NOT NULL,
  `imc` double NOT NULL,
  `tipo` enum('rutina','nutricional') DEFAULT 'rutina',
  `fecha` datetime NOT NULL,
  `videos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`videos`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `planes`
--

INSERT INTO `planes` (`id`, `usuario`, `contenido`, `estatura`, `peso`, `imc`, `tipo`, `fecha`, `videos`) VALUES
(1, '0', 'Perfecto, Gabriela. Con tu estatura de 1.55 metros y tu peso de 58 kg, calculando tu IMC, obtengo un valor de 24.1, lo que indica que te encuentras en un rango de peso saludable. \n\nPara aumentar masa muscular, te recomendaría una combinación de ejercicios de fuerza y resistencia. Aquí tienes una rutina de ejercicios para comenzar:\n\nDía 1: \n- Sentadillas: 3 series de 12 repeticiones\n- Flexiones de brazos: 3 series de 10 repeticiones\n- Peso muerto: 3 series de 12 repeticiones\n- Plancha: 3 series de 30 segundos\n\nDía 2: Descanso\n\nDía 3: \n- Press de banca: 3 series de 10 repeticiones\n- Remo con barra: 3 series de 12 repeticiones\n- Elevaciones laterales: 3 series de 12 repeticiones\n- Abdominales: 3 series de 15 repeticiones\n\nRecuerda calentar antes de iniciar tu rutina y estirar al terminar. Es ', 1.55, 55, 22.89, 'rutina', '2025-10-13 23:22:22', NULL),
(2, '0', 'Excelente, Fatima. Voy a diseñar un plan de alimentación balanceado para ti. \n\nPlan de Alimentación para Fatima:\n\nDesayuno:\n- 1 taza de avena con frutas frescas\n- 1 vaso de leche descremada o alternativa vegetal\n- 1 puñado de almendras o nueces\n\nAlmuerzo:\n- Ensalada de vegetales variados con pechuga de pollo a la plancha\n- 1 porción de quinoa o arroz integral\n- 1 pieza de fruta\n\nMerienda:\n- Yogur griego bajo en grasa con una cucharada de semillas de chía\n- 1 puñado de frutos rojos\n\nCena:\n- Salmón al horno con limón y especias\n- Verduras al vapor o asadas\n- 1 batata pequeña\n\nRecuerda beber al menos 2 litros de agua al día y reducir el consumo de alimentos procesados y azucarados. Además, es importante escuchar a tu cuerpo y no saltarte comidas.\n\n¿Deseas guardar este plan?', 1.48, 60, 27.39, 'rutina', '2025-10-13 23:51:46', NULL),
(6, 'Juan', '¡Excelente, Juan! ¿Deseas guardar este plan? Si tienes alguna otra pregunta o necesitas más ayuda, ¡aquí estaré para apoyarte!', 1.65, 70, 25.71, 'rutina', '2025-10-25 00:12:46', NULL),
(7, 'Karen', '¡Gracias por la información, Karen! Basándome en tus datos, tu Índice de Masa Corporal (IMC) es de aproximadamente 22.7. Esto indica que te encuentras en un rango saludable de peso.\n\nAhora, puedo proceder a diseñar un plan nutricional personalizado para ayudarte a aumentar masa muscular. Aquí tienes tu plan:\n\n---\n📍 **Plan Nutricional Personalizado**  \n- **Objetivo:** Aumento de masa muscular  \n- **Duración sugerida:** 8 semanas  \n- **Resumen del IMC:** Tu IMC es 22.7, lo cual indica un peso saludable.  \n- **Distribución diaria:**  \n  - **Desayuno:** Batido de proteínas con plátano y avena.  \n  - **Colación:** Yogur griego con frutos secos.  \n  - **Comida:** Pechuga de pollo a la plancha con quinoa y vegetales al vapor.  \n  - **Cena:** Salmón al horno con batata asada y brócoli.  \n- **Recom', 1.6, 58, 22.66, 'rutina', '2025-10-25 00:22:31', NULL),
(8, 'Eduardo', 'Mis disculpas por el corte anterior. Aquí continúo con tu rutina de ejercicio para aumentar masa muscular:\n\n---\n**Sesión tipo:**  \n- **Calentamiento:** 5–10 minutos de cardio ligero  \n- **Bloque principal:**  \n  1. Sentadillas: 4 series x 12 repeticiones  \n  2. Press de banca: 4 series x 10 repeticiones  \n  3. Peso muerto: 3 series x 12 repeticiones  \n  4. Dominadas: 3 series x 8 repeticiones  \n\n- **Enfriamiento/estiramiento:** Estirar los principales grupos musculares al final de la sesión.  \n\n**Consejos de progresión:** Aumenta progresivamente el peso y la intensidad de los ejercicios a medida que tu cuerpo se adapte. Descansa adecuadamente entre sesiones y asegúrate de mantener una alimentación balanceada para apoyar el desarrollo muscular.', 1.7, 65, 22.49, 'rutina', '2025-11-09 09:14:56', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `rol` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `rol`) VALUES
(1, 'usuario'),
(2, 'administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `telefono` bigint(11) NOT NULL,
  `edad` int(11) NOT NULL,
  `genero` varchar(255) NOT NULL,
  `correousuario` varchar(100) NOT NULL,
  `contrasena` varchar(50) NOT NULL,
  `google_id` varchar(255) NOT NULL,
  `foto_perfil` varchar(255) NOT NULL,
  `tipo_registro` enum('manual','google') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `telefono`, `edad`, `genero`, `correousuario`, `contrasena`, `google_id`, `foto_perfil`, `tipo_registro`) VALUES
(14, 'Fernanda', 'Gomez', 5544332211, 22, 'Mujer', 'fernanflor@gmail.com', '5NUqrPpET7OLGguYngyMCQ==', '', '', 'manual'),
(20, 'Liliana Itzel', 'Hernandez', 8877665544, 25, 'Mujer', 'lili25@gmail.com', '5NUqrPpET7OLGguYngyMCQ==', '', '', 'manual'),
(21, 'Itzel', 'Flores', 9988776655, 26, 'Mujer', 'itzflo@gmail.com', '5NUqrPpET7OLGguYngyMCQ==', '', '', 'manual'),
(22, 'Angel', 'Miguel', 3366998855, 28, 'Hombre', 'angel01@gmail.com', '5NUqrPpET7OLGguYngyMCQ==', '', '', 'manual'),
(23, 'Adrian', 'Rivas Hernández', 5590281928, 26, 'Hombre', 'adrianrivas30@gmial.com', 'okXq59jEjnPcfc2sY9kYdQ==', '', '', 'manual'),
(24, 'Dante', 'Rosas Torres', 5589201932, 30, 'Mujer', 'danterosas30@gmail.com', 'nqrS8ckcJGS07bZC9iUodQ==', '', '', 'manual'),
(25, 'Gabriela', 'Marques Torres', 5591284018, 28, 'Mujer', 'gabrielatorres20@gmail.com', 'CB22DhNgLdjbVOt6IRgAUg==', '', '', 'manual'),
(26, 'Paulo', 'Dario Rodriguez', 5529101120, 32, 'Hombre', 'paulolondra@gmial.com', 'Urq9jgeQIhzwQo/oELqoKA==', '', '', 'manual'),
(27, 'Mariano', 'Lopéz Rios ', 5521023958, 32, 'Hombre', 'marianorios20@gmial.com', 'okXq59jEjnPcfc2sY9kYdQ==', '', '', 'manual'),
(28, 'Fatima ', 'Torres Alcantara ', 5539201827, 23, 'Mujer', 'fatimatorres20@gmail.com', 'w1I4rbS1Nvc+5w/pWS+5zA==', '', '', 'manual'),
(29, 'Raul', 'Vasquez Janz', 5529183018, 34, 'Hombre', 'rauljanz30@gmial.com', 'USFRs2FvK3YptBxza77cbg==', '', '', 'manual'),
(30, 'Matias', 'Calleja ', 5528190219, 32, 'Mujer', 'matiascalleja20@gmail.com', 's68SKuF6rFvrCZ0ZPbXoOg==', '', '', 'manual'),
(32, 'Samuel', 'Krom Robles ', 5583191039, 26, 'Hombre', 'samuel@gmail.com', '5J+TOvi0wOmBkAJ4OfdQQQ==', '', '', 'manual'),
(33, 'Angel', 'Gómez Torres', 5590271829, 37, 'Hombre', 'angeltorres30@gmail.com', 'XG4bWcLuX56EY3WAGpTkwg==', '', '', 'manual'),
(34, 'Andrea', 'Rios Alcantara', 5532182910, 28, 'Mujer', 'andrearios20@gmail.com', 'SXUaDD37BLbBvsWpiK8Kgg==', '', '', 'manual'),
(35, 'Juan', 'Escutia Melgar', 5523193029, 28, 'Hombre', 'juanescutia28@gmail.com', '96blGj6iDBk+H/NPGHVLtw==', '', '', 'manual'),
(36, 'Karen', 'Palacios Ortiz', 5562491820, 30, 'Mujer', 'karenortiz30@gmail.com', 'ydHYv6FBe8D1U+QopLw+GA==', '', '', 'manual'),
(41, 'Gabriela', 'Dores Rios', 5589201821, 22, 'Mujer', 'gabyrios10@gmial.com', 'CB22DhNgLdjbVOt6IRgAUg==', '', '', 'manual'),
(43, 'Fernando', 'Solis Artiles', 5578291021, 21, 'Hombre', 'fernandosolis12@gmail.com', 'vzUhZyZ+A+m9hwnZcuCejA==', '', '', 'manual'),
(44, 'Eduardo', 'Rios Robles', 5590281928, 23, 'Hombre', 'eduardorios23@gmail.com', 'ydgFYwoR41b8+vo/ATUP9A==', '', '', 'manual'),
(46, 'Esteban Romero', 'Robles', 5578392102, 21, 'Hombre', 'estebanromerom18@gmail.com', '', '115077937055364262701', 'https://lh3.googleusercontent.com/a/ACg8ocIphy_c7aC90bPy8ntZaFPVmAsfkLxhrMq8mfnhUIeNBS3-hoh8=s96-c', 'google'),
(47, 'Brian ', 'Lopez Dorigan ', 5583291022, 23, 'Hombre', 'brianlopez25@gmail.com', 'l0doW4lw++/L5GqHqbd6Mw==', '', '', 'manual');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `videos_ejercicios`
--

CREATE TABLE `videos_ejercicios` (
  `id_video` int(11) NOT NULL,
  `nombre_ejercicio` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `videos_ejercicios`
--

INSERT INTO `videos_ejercicios` (`id_video`, `nombre_ejercicio`, `descripcion`, `video_url`, `fecha_registro`) VALUES
(1, 'Sentadillas', 'Ejercicio básico para piernas y glúteos.', 'https://www.youtube.com/embed/aclHkVaku9U', '2025-11-09 18:29:47'),
(2, 'Lagartijas', 'Ejercicio para fortalecer el pecho y brazos.', 'https://www.youtube.com/embed/IODxDxX7oi4', '2025-11-09 18:29:47'),
(3, 'Abdominales', 'Ejercicio clásico para el abdomen.', 'https://www.youtube.com/embed/1fbU_MkV7NE', '2025-11-09 18:29:47'),
(15, 'Abdominales2', 'Otra manera de hacer ejercicio', 'https://www.youtube.com/embed/CwhxepX7aR8?si=TSUXFtg2botuzo0L', '2025-11-10 05:36:09');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id_administrador`);

--
-- Indices de la tabla `planes`
--
ALTER TABLE `planes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `videos_ejercicios`
--
ALTER TABLE `videos_ejercicios`
  ADD PRIMARY KEY (`id_video`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrador`
--
ALTER TABLE `administrador`
  MODIFY `id_administrador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `planes`
--
ALTER TABLE `planes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `videos_ejercicios`
--
ALTER TABLE `videos_ejercicios`
  MODIFY `id_video` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
