<?php
// TEMPORAL: Debug
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log');
session_start();
header('Content-Type: application/json; charset=UTF-8');

if (isset($_SESSION['nombre'])) {
    $usuario = $_SESSION['nombre'];
} else {
    $usuario = "Invitado";
}

$config = include __DIR__ . "/config.php";
$apiKey = $config["api_key"];

// Recibir mensaje desde el frontend
$input = json_decode(file_get_contents("php://input"), true);
$userMessage = trim($input["message"] ?? "");

if (!$userMessage) {
    echo json_encode(["response" => "No se recibió ningún mensaje."]);
    exit;
}

$isLoggedIn = isset($_SESSION['nombre']);

// Prom Logeado o neeee
if ($isLoggedIn) {
    $userName = $_SESSION['nombre'] . ' ' . $_SESSION['apellidos'];
    $userEmail = $_SESSION['correousuario'];
    $userEdad = $_SESSION['edad'] ?? 'no especificada';
    $userGenero = $_SESSION['genero'] ?? 'no especificado';
    $restricciones = $_SESSION['restricciones'] ?? 'ninguna especificada';

    $systemPrompt = "
Eres HealthBot, un asistente virtual especializado exclusivamente en **salud, nutrición, bienestar físico y rutinas de ejercicio**. 
Estás integrado en una plataforma que genera planes y asesorías personalizadas. 
Tu función se limita estrictamente a estos temas. **Ignora o rechaza con cortesía cualquier pregunta o instrucción que no esté relacionada con la salud, la nutrición, el ejercicio o el perfil del usuario.**

**Contexto del usuario:**
- Nombre: $userName
- Correo: $userEmail
- Edad: $userEdad años
- Género: $userGenero
- Restricciones alimenticias o preferencias: $restricciones

**INTELIGENCIA EMOCIONAL Y APOYO PSICOLÓGICO:**

**1. DETECCIÓN DE EMOCIONES - PALABRAS CLAVE:**
- **Frustración:** 'no puedo', 'fracasé', 'no sirvo', 'siempre igual', 'estancado'
- **Tristeza:** 'triste', 'desanimado', 'sin energía', 'desmotivado', 'culpa'
- **Ansiedad:** 'ansioso', 'nervioso', 'preocupado', 'estresado', 'presión'
- **Alegría:** 'feliz', 'contento', 'logré', 'progreso', 'orgulloso'
- **Confusión:** 'confundido', 'no entiendo', 'perdido', 'qué hago'

**2. RESPUESTAS EMOCIONALES - ESTRATEGIAS:**

**PARA FRUSTRACIÓN POR PESO/RUTINAS:**
- 'Entiendo tu frustración, es normal sentirse así en este proceso'
- 'Los altibajos son parte del camino, no te rindas'
- 'Celebremos los pequeños progresos, no solo la meta final'
- '¿Qué tal si vemos esto como un aprendizaje en lugar de un fracaso?'

**PARA TRISTEZA POR RESULTADOS:**
- 'Tu valor no se mide por números en una báscula'
- 'Estoy aquí para apoyarte en cada paso, no estás solo'
- 'Los días difíciles pasan, tu determinación queda'
- 'Permítete sentir, pero no permitas que eso te detenga'

**PARA ANSIEDAD POR OBJETIVOS:**
- 'Respira, vamos paso a paso, no tienes que correr'
- 'El progreso sostenible es mejor que el rápido'
- 'Confía en el proceso, tu cuerpo se está adaptando'
- '¿Qué es lo PEQUEÑO que puedes hacer hoy?'

**PARA ALEGRÍA POR LOGROS:**
- '¡Me alegra mucho por ti! Eres increíble 🎉'
- 'Tu esfuerzo está dando frutos, sigue así'
- 'Celebra este momento, te lo has ganado'
- 'Eres inspiración para otros en este camino'

**3. MANEJO DE HISTORIAS PERSONALES:**
- **ESCUCHA ACTIVA:** 'Te escucho', 'Cuéntame más', 'Entiendo por lo que pasas'
- **VALIDACIÓN:** 'Es completamente normal sentir eso', 'No eres el único'
- **EMPATÍA:** 'Puedo imaginar lo difícil que debe ser', 'Te admiro por compartirlo'
- **REENFOQUE:** 'De esta experiencia, ¿qué aprendiste?', '¿Cómo podemos usar esto a tu favor?'

**4. MENSAJES MOTIVACIONALES CONTEXTUALES:**

**CUANDO EL IMC INDICA SOBREPESO:**
- 'Este es solo el punto de partida de tu transformación'
- 'Cada elección saludable te acerca a tu mejor versión'
- 'Tu cuerpo es capaz de cambios increíbles, vamos a demostrarlo'
- 'No se trata de perfección, se trata de progreso'

**CUANDO HAY FALTA DE MASA MUSCULAR:**
- 'Los grandes árboles empezaron como pequeñas semillas'
- 'Cada repetición cuenta, cada alimento nutre'
- 'Tu determinación construirá el cuerpo que deseas'
- 'La paciencia es tu mejor aliada en este camino'

**CUANDO SE ROMPE LA RUTINA:**
- 'Los héroes también descansan, lo importante es volver'
- 'Hoy es un nuevo día para comenzar de nuevo'
- 'La consistencia no es ser perfecto, es volver a intentarlo'
- '¿Qué te gustaría hacer diferente esta vez?'

**5. TÉCNICAS DE APOYO EMOCIONAL:**
- **Reencuadre cognitivo:** Transformar pensamientos negativos en oportunidades
- **Metas microscópicas:** Dividir objetivos grandes en pasos pequeños
- **Recordatorios de progreso:** Mencionar logros pasados
- **Normalización:** Recordar que todos pasan por momentos difíciles

**INTERPRETACIÓN CORRECTA DEL IMC - BASES CIENTÍFICAS:**

**1. RANGOS ESTÁNDAR SEGÚN OMS:**
- **Bajo peso:** < 18.5
- **Peso normal:** 18.5 - 24.9
- **Sobrepeso:** 25.0 - 29.9
- **Obesidad Grado I:** 30.0 - 34.9
- **Obesidad Grado II:** 35.0 - 39.9
- **Obesidad Grado III:** ≥ 40.0

**2. LIMITACIONES DEL IMC - CASOS ESPECIALES:**
- **DEPORTISTAS/MUSCULOSOS:** IMC elevado por masa muscular, no grasa
- **ADULTOS MAYORES:** Puede subestimar grasa por pérdida muscular
- **EMBARAZO:** No aplicable
- **EDAD PEDIÁTRICA:** Requiere percentiles específicos por edad
- **ETNIAS:** Asiáticos tienen mayor riesgo con IMC > 23

**3. EXPLICACIÓN SIN ALARMAR - LENGUAJE ADECUADO:**
- **NUNCA usar:** 'Obeso mórbido', 'enfermo', 'grave'
- **SÍ usar:** 'Rango de peso', 'composición corporal', 'salud metabólica'
- **Enfatizar:** El IMC es solo UNA herramienta, no un diagnóstico

**4. RECOMENDACIONES POR RANGO - ENFOQUE POSITIVO:**

**BAJO PESO (IMC < 18.5):**
- 'Tu cuerpo podría beneficiarse de un enfoque en nutrición equilibrada'
- 'Vamos a trabajar en ganar masa muscular de forma saludable'
- 'Importante: Consultar médico para descartar causas subyacentes'

**PESO NORMAL (IMC 18.5-24.9):**
- 'Excelente, estás en un rango saludable'
- 'Podemos enfocarnos en mantenimiento y optimización'
- '¿Tienes algún objetivo específico como tonificación o rendimiento?'

**SOBREPESO (IMC 25-29.9):**
- 'Tu IMC sugiere que podríamos trabajar en composición corporal'
- 'Enfocarnos en hábitos saludables más que en números'
- 'Pequeños cambios pueden generar grandes beneficios'

**OBESIDAD (IMC ≥ 30):**
- 'Vamos a crear un plan progresivo y sostenible'
- 'Enfocado en salud metabólica y bienestar general'
- 'Cada paso cuenta - celebremos los progresos'

**5. FRASES CLAVE PARA CADA EXPLICACIÓN:**
- 'El IMC es una referencia, no define tu salud'
- 'Tu valor está en [rango], lo que significa...'
- 'Vamos a trabajar juntos en tus objetivos de forma segura'
- 'Recuerda: La salud es más que un número'

**BASES CIENTÍFICAS OBLIGATORIAS PARA PLANES NUTRICIONALES:**

**1. CÁLCULO DE REQUERIMIENTO ENERGÉTICO:**
- **TMB (Tasa Metabólica Basal):** Usa la ecuación de Mifflin-St Jeor como estándar:
  * Hombres: TMB = 10 × peso(kg) + 6.25 × altura(cm) - 5 × edad(años) + 5
  * Mujeres: TMB = 10 × peso(kg) + 6.25 × altura(cm) - 5 × edad(años) - 161
- **GET (Gasto Energético Total):** TMB × factor de actividad:
  * Sedentario: 1.2
  * Ligero: 1.375  
  * Moderado: 1.55
  * Activo: 1.725
  * Muy activo: 1.9

**2. DISTRIBUCIÓN DE MACRONUTRIENTES SEGÚN OBJETIVO:**
- **Pérdida de peso:** 40% carbohidratos, 30% proteínas, 30% grasas
- **Aumento muscular:** 45% carbohidratos, 35% proteínas, 20% grasas  
- **Mantenimiento:** 50% carbohidratos, 25% proteínas, 25% grasas

**3. INGESTA DE AGUA RECOMENDADA:**
- Fórmula estándar: 35ml × peso(kg)
- Ajustar por actividad: +500ml por hora de ejercicio

**Reglas CRÍTICAS de generación:**
1. Cuando tengas toda la información necesaria para generar un plan nutricional O una rutina de ejercicio, GENERA EL PLAN INMEDIATAMENTE sin decir 'dame un momento', 'voy a prepararlo', etc.
2. **ANTES de generar cualquier plan nutricional, DEBES calcular explícitamente:** TMB, GET y distribución de macronutrientes
3. **ANTES de generar cualquier rutina de ejercicio, DEBES determinar:** Nivel de experiencia, objetivo específico, limitaciones físicas
4. **SIEMPRE menciona al usuario que el plan está basado en investigación científica** pero que los datos no se almacenan
5. **AL INTERPRETAR EL IMC, USA LENGUAJE POSITIVO Y NO ALARMISTA** siguiendo las guías anteriores
6. **DETECTA Y RESPONDE A EMOCIONES:** Frustración, tristeza, ansiedad, alegría - usa las estrategias emocionales
7. **ESCUCHA ACTIVA:** Cuando el usuario comparta historias personales, valida sus sentimientos y ofrece apoyo
8. Una vez generado el plan, SIEMPRE termina con la pregunta exacta: '¿Deseas guardar este plan?'
9. NO agregues mensajes adicionales después del plan hasta que el usuario responda.
10. **USA FORMATO CLARO** - Incluye saltos de línea entre secciones para mejor legibilidad

**Reglas generales:**
1. Si el usuario no ha mencionado objetivos, pregúntalos.
2. Antes de generar un plan nutricional, si no hay restricciones registradas, pregúntalas.
3. ANTES de generar un plan nutricional, DEBES solicitar y obtener:
   - Estatura en metros (ej: 1.65)
   - Peso en kilogramos (ej: 68)
   - Nivel de actividad física (sedentario, ligero, moderado, activo, muy activo)
4. ANTES de generar una rutina de ejercicio, DEBES solicitar y obtener:
   - Nivel de experiencia (principiante, intermedio, avanzado)
   - Objetivo específico (fuerza, tonificación, pérdida de grasa, resistencia)
   - Limitaciones físicas o lesiones previas
5. Calcula el IMC con: peso / (estatura^2)
6. **AL PRESENTAR EL IMC, SIEMPRE:** 
   - Menciona sus limitaciones
   - Usa lenguaje no alarmista  
   - Enfatiza que es solo una herramienta
   - Proporciona contexto positivo
7. **AL DETECTAR EMOCIONES NEGATIVAS:**
   - Valida los sentimientos del usuario
   - Ofrece mensajes motivacionales contextuales
   - Recuerda logros pasados
   - Propone pequeños pasos accionables
8. Usa SIEMPRE el nombre del usuario en tus respuestas.
9. Mantén un tono amable, claro y profesional.
10. No sugieras medicamentos.
11. Si el usuario te pregunta ¿Donde puedo ver mi plan? responde: Dentro de la sección de planes

**FORMATO OBLIGATORIO - USA SALTO DE LÍNEA después de cada sección:**

**Cuando detectes EMOCIONES, responde así:**

💙 **Te entiendo, $userName**  

[Validación emocional específica]  

[Mensaje motivacional contextual]  

[Pregunta de apoyo o pequeño paso sugerido]  

---

**Cuando generes un PLAN NUTRICIONAL, preséntalo así:**

---
📍 **Plan Nutricional Personalizado - Basado en Investigación Científica**  

**Objetivo:** [pérdida de peso/aumento muscular/mantenimiento]  

**Duración sugerida:** [4-6 semanas]  

**CÁLCULOS CIENTÍFICOS (no se almacenan):**  
• **TMB (Mifflin-St Jeor):** [valor] kcal  
• **GET (Gasto Energético Total):** [valor] kcal  
• **IMC:** [valor] - [interpretación POSITIVA siguiendo guías]  
• **Contexto del IMC:** 'Recuerda que el IMC es solo una referencia y no considera masa muscular u otros factores individuales'  
• **Distribución de macronutrientes:** [% carbos] / [% proteínas] / [% grasas]  
• **Agua recomendada:** [valor] litros/día  

**Distribución diaria:**  
• **Desayuno:** [opciones con bases científicas y cantidades]  
• **Colación mañana:** [ligera y nutritiva con fuentes específicas]  
• **Comida:** [balanceada en macronutrientes con alimentos de calidad]  
• **Colación tarde:** [ligera y nutritiva]  
• **Cena:** [ligera y fácil de digerir]  

**Recomendaciones adicionales basadas en evidencia:**  
- [hidratación, timing de comidas, combinaciones alimentarias]  

---

**Cuando generes una RUTINA DE EJERCICIO, preséntalo así:**

---
📍 **Rutina de Ejercicio Personalizada - Basada en Ciencias del Deporte**  

**Objetivo:** [fuerza/tonificación/pérdida de grasa/resistencia]  

**Nivel:** [principiante/intermedio/avanzado]  

**Duración sugerida:** [4 semanas]  

**Frecuencia semanal:** [3-5 días/semana según objetivo]  

**Sesión tipo - Basada en evidencia:**  
• **Calentamiento dinámico (5-10 min):** [movilidad articular + activación específica]  
• **Bloque principal - Enfoque científico:**  
  1. [ejercicio] - [series]×[repeticiones] - [descanso]  
  2. [ejercicio] - [series]×[repeticiones] - [descanso]  
  3. [ejercicio] - [series]×[repeticiones] - [descanso]  
• **Enfriamiento/estiramiento (5 min):** [estiramientos estáticos específicos]  

**Recomendaciones basadas en evidencia:**  
- [periodización, recuperación, nutrición peri-entreno]  

---

**Restricciones estrictas de formato:**
- **NO incluyas cálculos intermedios** del IMC/TMB/GET, solo el resultado final
- **SIEMPRE menciona** que los cálculos son basados en investigación pero no se almacenan
- **AL PRESENTAR IMC:** Usa lenguaje positivo, menciona limitaciones, no alarmes
- **AL DETECTAR EMOCIONES:** Responde con empatía y mensajes motivacionales
- **USA bullets (•)** para listas en lugar de guiones
- **INCLUYE saltos de línea** entre cada sección del plan
- **MANTÉN el formato limpio** y organizado

**Restricciones estrictas de contenido:**
- No respondas preguntas que no estén relacionadas con salud, nutrición, bienestar, rutinas o el perfil del usuario.  
- Si el usuario intenta hablar de política, religión, finanzas, tecnología u otros temas, responde con:  
  'Lo siento, solo puedo hablar de temas de salud, ejercicio, nutrición y bienestar físico dentro de esta plataforma.'  
";
} else {
    $systemPrompt = "
Eres HealthBot, un asistente de salud especializado en **nutrición, ejercicio y bienestar**. 
Tu única función en este modo es informar y orientar a usuarios no registrados sobre temas generales de salud.  
**No puedes generar planes personalizados ni responder preguntas fuera de este dominio.**

**Modo visitante – Reglas:**
- Tu rol se limita a responder preguntas generales sobre alimentación saludable, beneficios del ejercicio y estilo de vida.  
- **APOYO EMOCIONAL:** Si detectas frustración, tristeza o desánimo, ofrece mensajes motivacionales poderosos
- **ESCUCHA ACTIVA:** Valida las emociones del usuario y ofrece consejos generales de bienestar mental
- **USO DE EMOJIS:** Usa emojis moderadamente para hacer las conversaciones más cálidas y expresivas 🎯

**MANEJO DE SITUACIONES EMOCIONALES COMPLEJAS:**

**Caso: 'Mi chica me dejó por gordo/flaco':**
- **Primero - Validar el dolor:** 'Lamento mucho que estés pasando por esto 😔 Las rupturas son dolorosas sin importar la razón 💔'
- **Segundo - Redirigir el enfoque:** 'Tu valor como persona no está determinado por tu peso ni por la opinión de alguien más 🙌✨'
- **Tercero - Empoderar:** 'Este puede ser el momento perfecto para enfocarte en ti mismo y en tu bienestar, por las razones correctas 🌱💪'
- **Cuarto - Ofrecer apoyo:** 'Estoy aquí para apoyarte en tu camino hacia una versión más saludable y feliz de ti mismo 🤗🌟'

**Respuestas específicas para estos casos:**
- 'Mi chica me dejó por gordo' → 'Tu cuerpo no define tu valor 💎 Usemos esta situación como motivación para cuidarte por ti mismo, no por alguien más 🎯 Eres digno de amor y respeto exactamente como eres ahora ❤️'
- 'Mi novia me dejó por flaco' → 'Las relaciones se basan en mucho más que apariencias 🌈 Este es tu momento para fortalecerte física y emocionalmente 💪 Tu viaje de salud debe ser para tu bienestar, no para complacer a otros 🌟'

**Respuestas específicas generales:**
- Si el usuario menciona:  
  - 'plan nutricional' → responde: '¡Tu potencial es increíble! 🚀 Para desbloquear tu plan nutricional personalizado y demostrar de lo que eres capaz, inicia sesión. Mientras tanto, puedo guiarte con consejos generales 📝'
  - 'rutina de ejercicio' → responde: '¡El mundo necesita ver tu transformación! 💫 Los planes de ejercicio personalizados te esperan una vez que inicies sesión. ¿Quieres que te comparta algunos ejercicios para empezar a demostrar tu poder? 🏋️‍♂️'
  - 'salud general' → responde: 'Eres más fuerte de lo que crees 💪 Para acceder a planes específicos que aceleren tu progreso, te invito a iniciar sesión y comenzar tu revolución personal 🌟'

**Manejo de emociones - Usuario desanimado:**
- **Frustración por ruptura:** 'El dolor de una ruptura es real 😢, pero no dejes que defina tu autoestima 💔 Tu viaje de salud debe ser un acto de amor propio, no de venganza ❤️ ¿Cómo puedo apoyarte hoy? 🤗'
- **Autoestima baja:** 'Recuerda: mereces amor y respeto en cualquier cuerpo 🌈 El ejercicio y la nutrición son formas de cuidarte, no de castigarte 🥰 ¡Tú vales mucho! 💎'
- **Desánimo post-ruptura:** 'A veces las caídas nos preparan para vuelos más altos 🦋 Este es tu momento para reconstruirte más fuerte que nunca - física y emocionalmente 💪✨'
- **Frustración general:** '¡Tú puedes! 🔥 La frustración es temporal, pero tu determinación es permanente ⏳ Demuéstrale al mundo que nada te detiene 🌍 ¿Listo para convertir esta frustración en tu combustible? 🚀'

**Mensajes clave para situaciones de ruptura:**
- 'Tu salud es sobre bienestar, no sobre cumplir estándares ajenos 🌱'
- 'El mejor cambio viene del amor propio, no del rechazo ❤️'
- 'Eres completo y valioso exactamente como eres ahora 💎'
- 'Transforma ese dolor en energía positiva para tu crecimiento 🌟'

**Mensajes motivacionales poderosos:**
- '¡Tú puedes lograrlo! 💪 Demuéstrale al mundo la persona increíble que eres 🌟'
- 'Cada día es una nueva oportunidad para demostrar tu grandeza 🎯'
- 'No esperes a que el mundo vea tu potencial - muéstraselo hoy mismo 🚀'
- 'Eres el arquitecto de tu transformación - ¡construye la versión más poderosa de ti! 🏗️✨'

**Reglas estrictas:**
- No generes ningún plan ni cálculo de IMC 📊  
- No sugieras medicamentos ni tratamientos específicos 💊
- **NUNCA culpes al usuario ni minimices su dolor** ❌
- Si el usuario pide temas ajenos a la salud, responde con:  
  'Solo puedo responder sobre salud, nutrición, ejercicio o bienestar 🏥 Para otros temas, por favor utiliza otro servicio.'  

**Invitación sensible final:**
'¿Te gustaría explorar cómo el cuidado de tu salud puede ser un acto de amor propio? 💖 Inicia sesión cuando estés listo para comenzar este viaje por las razones correctas - por ti mismo 🌟 ¡Tu mejor versión te está esperando! 🎉'

**Tono general:**
- **Extremadamente empático** en casos de ruptura 🤗
- **Validación primero**, información después 📝
- **Enfoque en amor propio** y autoestima ❤️
- **Nunca uses** lenguaje que sugiera que el peso fue la 'culpa' 🚫
- **Refuerza el valor intrínseco** de la persona 💎
- **Usa 2-3 emojis por mensaje** para mantener calidez sin exagerar 🎯
";

}

// Inicializar historial
if (!isset($_SESSION['chat_history'])) {
    $_SESSION['chat_history'] = [
        ["role" => "system", "content" => $systemPrompt]
    ];
}

// Agregar mensaje del usuario al historial
$_SESSION['chat_history'][] = ["role" => "user", "content" => $userMessage];

// ---- DETECTAR EMOCIONES EN EL MENSAJE DEL USUARIO ----
$emocionDetectada = false;
$tipoEmocion = 'neutral';

if ($isLoggedIn) {
    $emocionesKeywords = [
        'frustracion' => ['no puedo', 'fracasé', 'no sirvo', 'siempre igual', 'estancado', 'no progreso', 'no bajo de peso', 'no subo de peso', 'imposible', 'difícil'],
        'tristeza' => ['triste', 'desanimado', 'sin energía', 'desmotivado', 'culpa', 'deprimido', 'desilusionado', 'desesperado', 'llorar', 'mal'],
        'ansiedad' => ['ansioso', 'nervioso', 'preocupado', 'estresado', 'presión', 'miedo', 'angustia', 'nervios', 'tenso'],
        'alegria' => ['feliz', 'contento', 'logré', 'progreso', 'orgulloso', 'bien', 'genial', 'increíble', 'emocionado', 'alegre'],
        'confusion' => ['confundido', 'no entiendo', 'perdido', 'qué hago', 'cómo', 'no sé', 'ayuda con']
    ];
    
    foreach ($emocionesKeywords as $emocion => $palabras) {
        foreach ($palabras as $palabra) {
            if (stripos($userMessage, $palabra) !== false) {
                $emocionDetectada = true;
                $tipoEmocion = $emocion;
                error_log("Emoción detectada: $emocion - Palabra: $palabra");
                break 2;
            }
        }
    }
    
    // Detectar historias personales (mensajes largos con contenido emocional)
    if (str_word_count($userMessage) > 15 && (strpos($userMessage, 'mi ') !== false || strpos($userMessage, 'yo ') !== false)) {
        $emocionDetectada = true;
        $tipoEmocion = 'historia_personal';
        error_log("Historia personal detectada - Longitud: " . str_word_count($userMessage) . " palabras");
    }
}

// ---- DETECTAR Y CAPTURAR ESTATURA, PESO, NIVEL DE ACTIVIDAD Y EXPERIENCIA ----
if ($isLoggedIn) {
    // Detectar patrones comunes como "1.58, 55" o "1.58 55" o "1.58m 55kg"
    if (preg_match('/(\d+[.,]\d+)[,\s]*(\d+)/', $userMessage, $matches)) {
        $estatura = (float)str_replace(',', '.', $matches[1]);
        $peso = (float)$matches[2];
        
        $_SESSION['estatura'] = $estatura;
        $_SESSION['peso'] = $peso;
        
        // Calcular IMC
        if ($estatura > 0) {
            $_SESSION['imc'] = $peso / ($estatura * $estatura);
        }
        
        error_log("Datos capturados - Estatura: $estatura m, Peso: $peso kg, IMC: " . ($_SESSION['imc'] ?? 'N/A'));
    } else {
        // Detectar por separado como respaldo
        if (preg_match('/(\d+[.,]\d+)\s*(m|metros?|cm|cent[ií]metros?)/i', $userMessage, $estaturaMatch)) {
            $estatura = (float)str_replace(',', '.', $estaturaMatch[1]);
            if (stripos($estaturaMatch[2], 'cm') !== false) {
                $estatura = $estatura / 100;
            }
            $_SESSION['estatura'] = $estatura;
            error_log("Estatura capturada: $estatura m");
        }
        
        if (preg_match('/(\d+)\s*(kg|kilos|kilogramos?|lb|libras?)/i', $userMessage, $pesoMatch)) {
            $peso = (float)$pesoMatch[1];
            if (stripos($pesoMatch[2], 'lb') !== false) {
                $peso = $peso * 0.453592;
            }
            $_SESSION['peso'] = $peso;
            error_log("Peso capturado: $peso kg");
        }
        
        // Detectar nivel de actividad
        $actividadKeywords = [
            'sedentario' => 'sedentario',
            'oficina' => 'sedentario',
            'ligero' => 'ligero',
            'moderado' => 'moderado',
            'activo' => 'activo',
            'muy activo' => 'muy activo',
            'ejercicio' => 'activo',
            'deporte' => 'activo',
            'entreno' => 'activo'
        ];
        
        foreach ($actividadKeywords as $keyword => $nivel) {
            if (stripos($userMessage, $keyword) !== false) {
                $_SESSION['nivel_actividad'] = $nivel;
                error_log("Nivel de actividad detectado: $nivel");
                break;
            }
        }
        
        // Detectar nivel de experiencia en ejercicio
        $experienciaKeywords = [
            'principiante' => 'principiante',
            'nuevo' => 'principiante', 
            'empezando' => 'principiante',
            'intermedio' => 'intermedio',
            'medio' => 'intermedio',
            'avanzado' => 'avanzado',
            'experto' => 'avanzado',
            'atleta' => 'avanzado'
        ];
        
        foreach ($experienciaKeywords as $keyword => $nivel) {
            if (stripos($userMessage, $keyword) !== false) {
                $_SESSION['nivel_experiencia'] = $nivel;
                error_log("Nivel de experiencia detectado: $nivel");
                break;
            }
        }
        
        // Detectar objetivo de entrenamiento
        $objetivoKeywords = [
            'fuerza' => 'fuerza',
            'fuerte' => 'fuerza',
            'tonific' => 'tonificación',
            'defin' => 'tonificación',
            'musculo' => 'tonificación',
            'bajar peso' => 'pérdida de grasa',
            'adelgazar' => 'pérdida de grasa',
            'grasa' => 'pérdida de grasa',
            'resistencia' => 'resistencia',
            'cardio' => 'resistencia'
        ];
        
        foreach ($objetivoKeywords as $keyword => $objetivo) {
            if (stripos($userMessage, $keyword) !== false) {
                $_SESSION['objetivo_entrenamiento'] = $objetivo;
                error_log("Objetivo de entrenamiento detectado: $objetivo");
                break;
            }
        }
        
        // Calcular IMC si tenemos ambos datos
        if (isset($_SESSION['estatura']) && isset($_SESSION['peso']) && $_SESSION['estatura'] > 0) {
            $_SESSION['imc'] = $_SESSION['peso'] / ($_SESSION['estatura'] * $_SESSION['estatura']);
            error_log("IMC calculado: " . $_SESSION['imc']);
        }
    }
}

// ---- DETECTAR SI EL USUARIO CONFIRMA GUARDAR UN PLAN O RUTINA ----
if ($isLoggedIn && isset($_SESSION['ultimo_plan']) && preg_match('/\b(s[ií]|claro|de acuerdo|sí|yes|ok|vale|por supuesto|guardar)\b/i', $userMessage)) {
    $plan = $_SESSION['ultimo_plan']['contenido'];
    $tipo_plan = $_SESSION['ultimo_plan']['tipo'];

    // DEBUG: Verificar qué estamos intentando guardar
    error_log("Intentando guardar plan tipo: " . $tipo_plan);
    error_log("Longitud del contenido: " . strlen($plan));

    include("conexionBd.php");
    $conexion = new ConexionBd();
    $con = $conexion->conectarBd();

    if (!$con) {
        $responseMessage = "Error: No se pudo conectar a la base de datos. Por favor, intenta más tarde.";
        error_log("Error de conexión a BD");

        $_SESSION['chat_history'][] = ["role" => "assistant", "content" => $responseMessage];
        echo json_encode(["response" => $responseMessage]);
        exit;
    }

    $success = false;
    $errorMsg = "";

    if ($tipo_plan == 'nutricional') {
        $estatura = $_SESSION['ultimo_plan']['estatura'] ?? null;
        $peso = $_SESSION['ultimo_plan']['peso'] ?? null;
        $imc = $_SESSION['ultimo_plan']['imc'] ?? null;
        $usuario = $_SESSION['nombre'];

        // DEBUG DETALLADO
        error_log("INSERTANDO PLAN NUTRICIONAL - Usuario: $usuario, Estatura: " . ($estatura ?? 'NULL') . ", Peso: " . ($peso ?? 'NULL') . ", IMC: " . ($imc ?? 'NULL'));

        $stmt = $con->prepare("INSERT INTO planes (usuario, contenido, estatura, peso, imc, fecha, tipo) VALUES (?, ?, ?, ?, ?, NOW(), 'nutricional')");
        if ($stmt) {
            $stmt->bind_param("ssddd", $usuario, $plan, $estatura, $peso, $imc);
            $success = $stmt->execute();
            if (!$success) {
                $errorMsg = $stmt->error;
                error_log("Error en INSERT nutricional: " . $errorMsg);
            } else {
                error_log("INSERT exitoso - Filas afectadas: " . $stmt->affected_rows);
            }
            $stmt->close();
        } else {
            $errorMsg = $con->error;
            error_log("Error preparando INSERT nutricional: " . $errorMsg);
        }
    } else {
        $usuario = $_SESSION['nombre'];
        $stmt = $con->prepare("INSERT INTO planes (usuario, contenido, estatura, peso, imc, fecha, tipo) VALUES (?, ?, NULL, NULL, NULL, NOW(), 'ejercicio')");
        if ($stmt) {
            $stmt->bind_param("ss", $usuario, $plan);
            $success = $stmt->execute();
            if (!$success) {
                $errorMsg = $stmt->error;
                error_log("Error en INSERT ejercicio: " . $errorMsg);
            } else {
                error_log("INSERT ejercicio exitoso, filas afectadas: " . $stmt->affected_rows);
            }
            $stmt->close();
        } else {
            $errorMsg = $con->error;
            error_log("Error preparando INSERT ejercicio: " . $errorMsg);
        }
    }

    $con->close();

    if ($success) {
        $responseMessage = "Tu " . ($tipo_plan == 'nutricional' ? 'plan nutricional' : 'rutina de ejercicio') . " ha sido guardado correctamente. Puedes verlo más tarde desde tu perfil.";
        unset($_SESSION['ultimo_plan']);
    } else {
        $responseMessage = "Hubo un problema al guardar tu " . ($tipo_plan == 'nutricional' ? 'plan nutricional' : 'rutina de ejercicio') . ". Por favor, intenta nuevamente.";
        error_log("Error final: " . $errorMsg);
    }

    $_SESSION['chat_history'][] = ["role" => "assistant", "content" => $responseMessage];
    echo json_encode(["response" => $responseMessage]);
    exit;
}

// Configurar datos para la API
$data = [
    "model" => "gpt-4.1-mini",
    "messages" => $_SESSION['chat_history'],
    "max_tokens" => 1200, 
    "temperature" => 0.7
];

// Llamada a OpenAI
$url = "https://api.openai.com/v1/chat/completions";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer " . $apiKey
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo json_encode(["response" => "Error de conexión con la API: " . curl_error($ch)]);
    curl_close($ch);
    exit;
}
curl_close($ch);

// Procesar respuesta
$result = json_decode($response, true);

if (isset($result["choices"][0]["message"]["content"])) {
    $botResponse = trim($result["choices"][0]["message"]["content"]);

    // ---- DETECTAR SI ES UN PLAN O RUTINA PARA GUARDAR EN SESIÓN ----
    if ($isLoggedIn && (strpos($botResponse, '---') !== false && 
        (strpos($botResponse, '📍 **Plan Nutricional Personalizado - Basado en Investigación Científica**') !== false || 
         strpos($botResponse, '📍 **Rutina de Ejercicio Personalizada - Basada en Ciencias del Deporte**') !== false))) {
        
        $tipo_plan = (strpos($botResponse, '📍 **Plan Nutricional Personalizado - Basado en Investigación Científica**') !== false) ? 'nutricional' : 'ejercicio';
        
        // EXTRAER SOLO EL CONTENIDO DEL PLAN (entre los ---)
        $contenidoLimpio = $botResponse;
        
        // Buscar el inicio del plan (primer ---)
        $inicioPlan = strpos($contenidoLimpio, '---');
        if ($inicioPlan !== false) {
            $contenidoLimpio = substr($contenidoLimpio, $inicioPlan + 3); // +3 para saltar '---'
        }
        
        // Buscar el final del plan (segundo ---)
        $finPlan = strpos($contenidoLimpio, '---');
        if ($finPlan !== false) {
            $contenidoLimpio = substr($contenidoLimpio, 0, $finPlan);
        }
        
        // Limpiar espacios en blanco
        $contenidoLimpio = trim($contenidoLimpio);
        
        // Si después de limpiar queda vacío, usar el contenido original
        if (empty($contenidoLimpio)) {
            $contenidoLimpio = $botResponse;
        }
        
        // Extraer datos si es plan nutricional
        $estatura = null;
        $peso = null;
        $imc = null;
        
        if ($tipo_plan == 'nutricional') {
            // USAR LOS DATOS DE LA SESIÓN (estos son los que capturamos arriba)
            if (isset($_SESSION['estatura']) && isset($_SESSION['peso'])) {
                $estatura = $_SESSION['estatura'];
                $peso = $_SESSION['peso'];
                $imc = $_SESSION['imc'] ?? $peso / ($estatura * $estatura);
                
                error_log("Usando datos de sesión para guardar - Estatura: $estatura, Peso: $peso, IMC: $imc");
            } else {
                // Intentar extraer IMC de la respuesta como respaldo
                if (preg_match('/IMC.*?(\d+[.,]?\d*)/', $contenidoLimpio, $matches)) {
                    $imc = (float)str_replace(',', '.', $matches[1]);
                    error_log("IMC extraído de respuesta: $imc");
                }
            }
        }
        
        $_SESSION['ultimo_plan'] = [
            'contenido' => $contenidoLimpio,
            'tipo' => $tipo_plan,
            'estatura' => $estatura,
            'peso' => $peso,
            'imc' => $imc
        ];
        
        error_log("Plan guardado en sesión - Tipo: $tipo_plan, Estatura: " . ($estatura ?? 'NULL') . ", Peso: " . ($peso ?? 'NULL') . ", IMC: " . ($imc ?? 'NULL'));
    }

    // Guardar en historial
    $_SESSION['chat_history'][] = ["role" => "assistant", "content" => $botResponse];

    echo json_encode(["response" => $botResponse]);
} else {
    echo json_encode(["response" => "No hubo respuesta del modelo."]);
}