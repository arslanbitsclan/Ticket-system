@if( $col == 'uid' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Ticket ID">{{$survey->uid ?? 'N/A'}}</span>

@elseif( $col == 'subject' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Ticket subject">{{$survey->subject ?? 'N/A'}}</span>
    
@elseif( $col == 'customer_name' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Customer name">{{$survey->customer_name ?? 'N/A'}}</span>
    
@elseif( $col == 'phone' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Customer phone">{{$survey->phone ?? 'N/A'}}</span>
    
@elseif( $col == 'assign_name' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Assigned user">{{$survey->assign_name ?? 'N/A'}}</span>
    
@elseif( $col == 'call_type' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Assigned user">{{$survey->call_type == "1" ? 'Request' : 'Inquiry'}}</span>
    
@elseif( $col == 'created_at' )
    <div class="@if(!auth()->user()->can('edit-ticket') && !auth()->user()->can('delete-ticket') ) text-end  @endif">
        <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Created date"> {{ showTwelveHourDateTime($survey->created_at) }} </span>
    </div>

@elseif( $col == 'actions' )
    @canany(['edit-survey','delete-survey'])
    <div class="text-end">
        @can('edit-survey')
        <a href="{{ route('survey.edit', $survey->id) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Edit Survey"><i class="fa-solid fa-pen-to-square"></i></a>
        @endcan
        
        @can('delete-survey')
        <button type="button" class="btn btn-sm btn-icon btn-danger delete" data-delete-id = {{ $survey->id}} data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Delete Survey"><i class="fa-sharp fa-solid fa-trash"></i></button>
        @endcan
    </div>
    @endcanany
@endif
