<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WialonTokenDeleted extends Notification
{
    use Queueable;

    public $nombre;
    public $servicio;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($nombre, $servicio)
    {
      $this->nombre    = $nombre;
      $this->servicio = $servicio;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
              ->from(env('MAIL_FROM_ADDRESS'), config('app.name'))
              ->greeting('Saludos '. $this->nombre)
              ->subject('Su token de Wialon fue eliminado')
              ->line('Este correo es para notificarle que su Token de Wialon para el servicio:' . ($this->servicio->alias ?? '#'.$this->servicio->id) . ' fue eliminado.')
              ->line('Esto puede deberse a que ha ocurrido un error con el Token o que el Token ha expirado. Para mayor información puede consultar los Logs de su servicio.')
              ->line('Le recomendamos que inicie sesión en su cuenta y genere un nuevo Token de Wialon para reestablecer el funcionamiento del servicio.')
              ->action('Generar nuevo Token', route('servicios.edit', ['servicio' => $this->servicio->id]))
              ->line('Si recibiste este correo por error, te agradecemos que lo elimines.')
              ->salutation('Saludos, '. config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
