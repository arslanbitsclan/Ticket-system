@if( $col == 'name' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Organization Name">{{$organization->name ?? 'N/A'}}</span>
    
@elseif( $col == 'email' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Email">{{$organization->email ?? 'N/A'}}</span>
    
@elseif( $col == 'phone' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Role">{{$organization->phone ?? 'N/A'}}</span>
    
@elseif( $col == 'created_at' )
    <div class="@if(!auth()->user()->can('edit-organization') && !auth()->user()->can('delete-organization') ) text-end  @endif">
        <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Created Date"> {{ showTwelveHourDateTime($organization->created_at) }} </span>
    </div>

@elseif( $col == 'actions' )
    @canany(['edit-organization','delete-organization'])
    <div class="text-end">
        @can('edit-organization')
        <a href="{{ route('organization.edit', $organization->id) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Edit Role"><i class="fa-solid fa-pen-to-square"></i></a>
        @endcan
        
        @can('delete-organization')
        <button type="button" class="btn btn-sm btn-icon btn-danger delete" data-delete-id = {{ $organization->id}} data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Delete Role"><i class="fa-sharp fa-solid fa-trash"></i></button>
        @endcan
    </div>
    @endcanany
@endif
