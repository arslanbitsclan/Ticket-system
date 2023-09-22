@if( $col == 'first_name' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="First Name">{{$contact->first_name ?? 'N/A'}}</span>

@elseif( $col == 'last_name' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Last Name">{{$contact->last_name ?? 'N/A'}}</span>
    
@elseif( $col == 'organization_id' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Organization Name">{{$contact->organization_name ?? 'N/A'}}</span>
    
@elseif( $col == 'email' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Email">{{$contact->email ?? 'N/A'}}</span>
       
@elseif( $col == 'phone' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Phone">{{$contact->phone ?? 'N/A'}}</span>
    
@elseif( $col == 'created_at' )
    <div class="@if(!auth()->user()->can('edit-contact') && !auth()->user()->can('delete-contact') ) text-end  @endif">
        <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Created Date"> {{ showTwelveHourDateTime($contact->created_at) }} </span>
    </div>

@elseif( $col == 'actions' )
    @canany(['edit-contact','delete-contact'])
    <div class="text-end">
        @can('edit-contact')
        <a href="{{ route('contact.edit', $contact->id) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Edit Role"><i class="fa-solid fa-pen-to-square"></i></a>
        @endcan
        
        @can('delete-contact')
        <button type="button" class="btn btn-sm btn-icon btn-danger delete" data-delete-id = {{ $contact->id}} data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Delete Role"><i class="fa-sharp fa-solid fa-trash"></i></button>
        @endcan
    </div>
    @endcanany
@endif
