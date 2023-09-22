@if( $col == 'first_name' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="First name">{{$customer->first_name ?? 'N/A'}}</span>

@elseif( $col == 'last_name' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Last name">{{$customer->last_name ?? 'N/A'}}</span>
    
@elseif( $col == 'email' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Email">{{$customer->email ?? 'N/A'}}</span>
    
@elseif( $col == 'role' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Role">{{$customer->roles->pluck('name')[0] ?? 'N/A'}}</span>
    
@elseif( $col == 'created_at' )
    <div class="@if(!auth()->user()->can('edit-user') && !auth()->user()->can('delete-user') ) text-end  @endif">
        <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Created date"> {{ showTwelveHourDateTime($customer->created_at) }} </span>
    </div>

@elseif( $col == 'actions' )
    @canany(['edit-customer','delete-customer'])
    <div class="text-end">
        @can('edit-customer')
        <a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Edit Role"><i class="fa-solid fa-pen-to-square"></i></a>
        @endcan
        
        @can('delete-customer')
        <button type="button" class="btn btn-sm btn-icon btn-danger delete" data-delete-id = {{ $customer->id}} data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Delete Role"><i class="fa-sharp fa-solid fa-trash"></i></button>
        @endcan
    </div>
    @endcanany
@endif
