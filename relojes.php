<?php

abstract class AbstractReloj
{
    /**
     * Convierte segundos en horas,minutos y segundos. Tal como aparecería en un reloj digital
     *
     * @param  int  $n
     * @return string
     */
    protected function convertSecondsToFullTime($n){
        $n = ($n % (24 * 3600));
        $hour = $this->formatTimeToString($n / 3600);
     
        $n %= 3600;
        $minutes = $this->formatTimeToString($n / 60) ;
     
        $n %= 60;
        $seconds = $this->formatTimeToString($n);

        return $hour.$minutes.$seconds;
    }

    /**
     * Formatea el tiempo volviendo un numero entero, en caso de que sea de un slo digito le agrega el 0
     *
     * @param  int  $time
     * @return string
     */
    private function formatTimeToString($time){
        //Transforma float a int
        $formattedTime=intval($time);

        //Si el caracter tiene un solo numero agrega un cero adelante
        if(strlen($formattedTime)<2){
            $formattedTime='0'.$formattedTime;
        }

        return strval($formattedTime);
    }

    /**
     * Calculo el gasto energético segun el número recibido
     *
     * @param  string $fullTimeString
     * @return int
     */
    protected function calcGastoEnergetico(string $fullTimeString):int{
        $totalTimeStringConsume=0;

        for ($i = 0; $i < strlen($fullTimeString); $i++){
            
            switch ($fullTimeString[$i]) {
                case '0':
                case '9':
                case '6':
                    $totalTimeStringConsume+= 6;
                    break;
                case '1':
                    $totalTimeStringConsume+= 2;
                    break;
                case '2':
                case '3':
                case '5':
                    $totalTimeStringConsume+= 5;
                    break;
                case '4':
                    $totalTimeStringConsume+= 4;
                    break;
                case '7':
                    $totalTimeStringConsume+= 3;
                    break;
                case '8':
                    $totalTimeStringConsume+= 7;
                    break;
                default:
                    $totalTimeStringConsume+= 0;
                    break;
            }
        }

        return $totalTimeStringConsume;
    }
    
    /**
     * Se obtiene el gasto energético según los segundos recibidos
     *
     * @param  int $seconds
     * @return int
     */
    abstract protected function getGastoEnergetico(int $seconds):int;
}

class RelojEstandar extends AbstractReloj
{
    /**
     * Se obtiene el gasto energético según los segundos recibidos
     *
     * @param  int $seconds
     * @return int
     */
    public function getGastoEnergetico(int $seconds):int
    {
        $totalGasto=0;

        for ($i = 0; $i <= $seconds; $i++){
            
            $currentTimeString=$this->convertSecondsToFullTime($i);

            $totalGasto+=$this->calcGastoEnergetico($currentTimeString);
        }

        return $totalGasto;
    }
}

class RelojPremium extends AbstractReloj
{
    /**
     * Se obtiene el gasto energético según los segundos recibidos
     *
     * @param  int $seconds
     * @return int
     */
    public function getGastoEnergetico(int $seconds):int
    {
        $totalGasto=0;
        $lastClocktime=0;

        //Por cada segundo se ejecuta un cálculo
        for ($i = 0; $i <= $seconds; $i++){

            //En la primer pasada se caclula el gasto energetico de todo el tiempo
            if($i==0){
                $timeString=$this->convertSecondsToFullTime($i);
                $totalGasto+=$this->calcGastoEnergetico($timeString);
                $lastClocktime = $timeString;
            }else{
                //A partir de la segunda pasada, se calcula solo los números diferentes
                $current_time = $this->convertSecondsToFullTime($i);
                $diff_arr=$this->calcDiffTime($lastClocktime,$current_time);

                //El gasto energético va a ser igual a la suma del gasto energetico de todos los números diferentes
                foreach ($diff_arr as $diff) {
                    $totalGasto+=$this->calcGastoEnergetico($diff);
                }
                //El nuevo timpo que marca el reloj pasa a ser el tiempo viejo para iniciar denuevo el bucle
                $lastClocktime = $current_time;
            }      
        }

        return $totalGasto;
    }

    /**
     * Se calcula la diferencia entre el horario a reemplazar en el reloj digital y el nuevo horario
     *
     * @param  string $lastClocktime 
     * @param  string $current_time
     * @return array
     */
    private function calcDiffTime(string $lastClocktime, string $current_time ):array
    {
        $lastClocktime_arr=str_split($lastClocktime);
        $current_time_arr=str_split($current_time);
        return array_diff_assoc($current_time_arr,$lastClocktime_arr);  
    }
}

if (!function_exists('calcAhorroEnergia'))
{
    /**
     * Se calcula el ahorro de energía durante un día
     *
     * @param   
     * @return int
     */
    function calcAhorroEnergia(){
        $secondsInADay=86400;
    
        $relojEstandar = new RelojEstandar();
        $gastoRelojEstandar     = $relojEstandar->getGastoEnergetico($secondsInADay);
    
        $relojPremium = new RelojPremium();
        $gastoRelojPremium    = $relojPremium->getGastoEnergetico($secondsInADay);
    
        return $gastoRelojEstandar - $gastoRelojPremium;
    }
}


$ahorro=calcAhorroEnergia();

// Casos de Prueba
$relojEstandar = new RelojEstandar();
$resultado     = $relojEstandar->getGastoEnergetico(0);
echo 'Reloj Estandar  (0seg)     : ' . $resultado . "\n";
$resultado = $relojEstandar->getGastoEnergetico(4);
echo 'Reloj Estandar (4seg)      : ' . $resultado . "\n";

$relojPremium = new RelojPremium();
$resultado    = $relojPremium->getGastoEnergetico(0);
echo 'Reloj Premium  (0seg)      : ' . $resultado . "\n";
$resultado = $relojPremium->getGastoEnergetico(4);
echo 'Reloj Premium (4seg)       : ' . $resultado . "\n";

// Completar con resolucion de punto 2
echo 'Ahorro Premium vs Estandar : ' . $ahorro . "\n";