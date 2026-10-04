<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Client;
use App\Models\WorkOrder;
use Illuminate\Support\Str;

class InvoiceIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $showModal = false;

    // Formulario Cabecera
    public $client_id;
    public $issue_date;
    public $due_date;
    public $tax_rate = 21.00;

    // Formulario Líneas
    public $items = [];

    public function mount($work_order_id = null)
{
    $this->issue_date = now()->format('Y-m-d');
    $this->due_date = now()->addDays(30)->format('Y-m-d');

    // Si viene una Orden de Trabajo por parámetro en la URL
    if ($work_order_id) {
        $workOrder = WorkOrder::find($work_order_id);

        if ($workOrder) {
            $this->client_id = $workOrder->client_id;
            $this->items = [
                [
                    'work_order_id' => $workOrder->id,
                    'description'   => 'Trabajos realizados según Orden de Trabajo #' . $workOrder->id,
                    'quantity'      => 1,
                    'unit_price'    => $workOrder->total_cost ?? 0.00,
                    'total_price'   => $workOrder->total_cost ?? 0.00,
                ]
            ];
            $this->showModal = true; // Abre el formulario de factura de inmediato
            return;
        }
    }

    $this->addItem();
}

    public function addItem()
    {
        $this->items[] = [
            'work_order_id' => null,
            'description'   => '',
            'quantity'      => 1,
            'unit_price'    => 0.00,
            'total_price'   => 0.00,
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function updateItemTotal($index)
    {
        $qty = intval($this->items[$index]['quantity'] ?? 0);
        $price = floatval($this->items[$index]['unit_price'] ?? 0);
        $this->items[$index]['total_price'] = round($qty * $price, 2);
    }

    public function openCreateModal()
    {
        $this->reset(['client_id']);
        $this->issue_date = now()->format('Y-m-d');
        $this->due_date = now()->addDays(30)->format('Y-m-d');
        $this->items = [];
        $this->addItem();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function save()
    {
        $this->validate([
            'client_id'             => 'required|exists:clients,id',
            'issue_date'            => 'required|date',
            'due_date'              => 'required|date',
            'tax_rate'              => 'required|numeric|min:0|max:100',
            'items'                 => 'required|array|min:1',
            'items.*.description'   => 'required|string|max:255',
            'items.*.quantity'      => 'required|integer|min:1',
            'items.*.unit_price'    => 'required|numeric|min:0',
            'items.*.work_order_id' => 'nullable|exists:work_orders,id',
        ]);

        $year = now()->format('Y');
        $lastInvoice = Invoice::whereYear('created_at', $year)->latest()->first();
        $nextNum = $lastInvoice ? (int) Str::afterLast($lastInvoice->invoice_number, '-') + 1 : 1;
        $invoiceNumber = 'FAC-' . $year . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

        $subtotal = 0;
        foreach ($this->items as $item) {
            $subtotal += round($item['quantity'] * $item['unit_price'], 2);
        }

        $taxAmount = round($subtotal * ($this->tax_rate / 100), 2);
        $total = $subtotal + $taxAmount;

        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'client_id'      => $this->client_id,
            'issue_date'     => $this->issue_date,
            'due_date'       => $this->due_date,
            'subtotal'       => $subtotal,
            'tax_rate'       => $this->tax_rate,
            'tax_amount'     => $taxAmount,
            'total'          => $total,
            'status'         => 'issued',
        ]);

        foreach ($this->items as $item) {
            InvoiceItem::create([
                'invoice_id'    => $invoice->id,
                'work_order_id' => $item['work_order_id'] ?: null,
                'description'   => $item['description'],
                'quantity'      => $item['quantity'],
                'unit_price'    => $item['unit_price'],
                'total_price'   => round($item['quantity'] * $item['unit_price'], 2),
            ]);

            if (!empty($item['work_order_id'])) {
                WorkOrder::where('id', $item['work_order_id'])->update(['status' => 'invoiced']);
            }
        }

        $this->showModal = false;
        session()->flash('message', 'Factura ' . $invoiceNumber . ' creada exitosamente.');
    }

    public function markAsPaid($invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        $invoice->update(['status' => 'paid']);
        session()->flash('message', 'Factura ' . $invoice->invoice_number . ' cobrada.');
    }

    public function render()
    {
        $invoices = Invoice::with(['client', 'items.workOrder'])
            ->when($this->search, function ($q) {
                $q->where('invoice_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('client', fn($c) => $c->where('name', 'like', '%' . $this->search . '%'));
            })
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(10);

        return view('livewire.invoice-index', [
            'invoices'   => $invoices,
            'clients'    => Client::orderBy('name')->get(),
            'workOrders' => WorkOrder::where('status', 'completed')->get(),
        ]);
    }
}