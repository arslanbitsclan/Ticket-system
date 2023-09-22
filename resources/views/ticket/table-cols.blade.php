@if( $col == 'uid' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Ticket ID">{{$ticket->uid ?? 'N/A'}}</span>

@elseif( $col == 'subject' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Ticket subject">{{$ticket->subject ?? 'N/A'}}</span>
    
@elseif( $col == 'user_name' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Customer name">{{$ticket->user_name ?? 'N/A'}}</span>
    
@elseif( $col == 'priority_name' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Priority name">{{$ticket->priority_name ?? 'N/A'}}</span>
    
@elseif( $col == 'status_name' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Status name">{{$ticket->status_name ?? 'N/A'}}</span>
    
@elseif( $col == 'created_at' )
    <div class="@if(!auth()->user()->can('edit-ticket') && !auth()->user()->can('delete-ticket') ) text-end  @endif">
        <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Created date"> {{ showTwelveHourDateTime($ticket->created_at) }} </span>
    </div>

@elseif( $col == 'actions' )
    @canany(['edit-ticket','delete-ticket'])
    <div class="text-end">
        @can('edit-ticket')
        <a href="{{ route('ticket.edit', $ticket->id) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Edit Role"><i class="fa-solid fa-pen-to-square"></i></a>
        @endcan
        
        @can('delete-ticket')
        <button type="button" class="btn btn-sm btn-icon btn-danger delete" data-delete-id = {{ $ticket->id}} data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Delete Role"><i class="fa-sharp fa-solid fa-trash"></i></button>
        @endcan
    </div>
    @endcanany
@endif
