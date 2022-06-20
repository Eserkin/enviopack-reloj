# Los 2 Relojes
Supongamos que tenemos dos relojes como se representa en la imagen inferior, el mismo ilumina cada número por alguna combinacion de 7 leds.

![image](https://user-images.githubusercontent.com/11895814/174517379-81fe9652-581b-4659-aa99-3426a3d8b6c6.png)


Los números a ser representados estan formados por las siguientes combinacion de leds

![image](https://user-images.githubusercontent.com/11895814/174517394-4af278e7-ab1c-4261-9542-664a2b1c2ca5.png)


Teniendo en cuenta que:

El reloj solo gasta energía al prender cada led.
Prender un led tiene un gasto energético de un microwatt (μW)
Y que en cada segundo:

### En un Reloj Estándar
Cada vez que se quiere representar una hora distinta del reloj, se apagan por completo todos los leds, de todos los números y se encienden los leds correspondientes al siguiente horario.
En un Reloj Premium
Cada vez que se quiera representar una hora distinta, solamente se van a apagar y prender los leds necesarios para formar los números que forman el siguiente horario.
Realice
Una clase PHP para cada tipo reloj que permita calcular mediante un método getGastoEnergetico la cantidad de microwatts (μW) consumidos pasados "n" segundos desde el reloj en estado inicial (00:00:00).
Calcule cuanto es el ahorro de energía en watts (w) durante un dia completo, respecto de usar un reloj premium vs un reloj estandar. Asumiendo que el reloj se prende por primera vez a las 00:00:00 del dia.

### Casos de prueba
```
$relojEstandar = new RelojEstandar();
echo $relojEstandar->getGastoEnergetico(0); // Response: 36
echo $relojEstandar->getGastoEnergetico(4); // Response: 172

$relojPremium = new RelojPremium();
echo $relojPremium->getGastoEnergetico(0); // Response: 36
echo $relojPremium->getGastoEnergetico(4); // Response: 42
```
