@if( $col == 'first_name' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="First name">{{$user->first_name ?? 'N/A'}}</span>

@elseif( $col == 'last_name' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Last name">{{$user->last_name ?? 'N/A'}}</span>
    
@elseif( $col == 'email' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Email">{{$user->email ?? 'N/A'}}</span>
    
@elseif( $col == 'role' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Role">{{$user->roles->pluck('name')[0] ?? 'N/A'}}</span>
    
@elseif( $col == 'created_at' )
    <div class="@if(!auth()->user()->can('edit-user') && !auth()->user()->can('delete-user') ) text-end  @endif">
        <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Created date"> {{ showTwelveHourDateTime($user->created_at) }} </span>
    </div>

@elseif( $col == 'actions' )
    @canany(['edit-user','delete-user'])
    <div class="text-end">
        @can('edit-user')
        <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Edit Role"><i class="fa-solid fa-pen-to-square"></i></a>
        @endcan
        
        @can('delete-user')
        <button type="button" class="btn btn-sm btn-icon btn-danger delete" data-delete-id = {{ $user->id}} data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Delete Role"><i class="fa-sharp fa-solid fa-trash"></i></button>
        @endcan
    </div>
    @endcanany
@endif
