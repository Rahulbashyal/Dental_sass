<?php

namespace App\Notifications;

use App\Models\InventoryItem;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockAlert extends Notification
{
    use Queueable;

    protected $item;

    public function __construct(InventoryItem $item)
    {
        $this->item = $item;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Low Stock Alert: ' . $this->item->name)
            ->greeting('Hello!')
            ->line('The stock level for **' . $this->item->name . '** has reached its minimum threshold.')
            ->line('**Current Stock:** ' . $this->item->current_stock . ' ' . $this->item->unit)
            ->line('**Minimum Required:** ' . $this->item->min_stock_level)
            ->action('View Inventory', route('clinic.inventory.index'))
            ->line('Please restock soon to avoid disruptions in clinical operations.');
    }

    public function toArray($notifiable)
    {
        return [
            'item_id' => $this->item->id,
            'item_name' => $this->item->name,
            'current_stock' => $this->item->current_stock,
            'message' => 'Low stock level for ' . $this->item->name,
            'type' => 'warning',
            'action_url' => route('clinic.inventory.index'),
        ];
    }
}
