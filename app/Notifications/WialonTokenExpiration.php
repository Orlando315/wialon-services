<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WialonTokenExpiration extends Notification
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
              ->subject('Su token de Wialon esta por expirar')
              ->line('Este correo es para notificarle que su Token de Wialon para el servicio:' . ($this->servicio->alias ?? '#'.$this->servicio->id) . ' expira el: ' . $this->servicio->wialon_expiration . '.')
              ->line('Le recomendamos que inicie sesión en su cuenta, elimine el token actual e inicie sesión en Wialon para evitar interrupciones en el servicio.')
              ->action('Actualizar token', route('servicios.edit', ['servicio' => $this->servicio->id]))
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
