@if( $col == 'name' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Role name">{{$role->name ?? 'N/A'}}</span>

@elseif( $col == 'created_at' )
    <div class="@if(!auth()->user()->can('edit-role') && !auth()->user()->can('delete-role') ) text-end  @endif">
        <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Created date"> {{ showTwelveHourDateTime($role->created_at) }} </span>
    </div>

@elseif( $col == 'actions' )
    @canany(['edit-role','delete-role'])
    <div class="text-end">
        @can('edit-role')
        <a href="{{ route('role.edit', $role->id) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Edit Role"><i class="fa-solid fa-pen-to-square"></i></a>
        @endcan
        
         @can('delete-role')
        <button type="button" class="btn btn-sm btn-icon btn-danger delete" data-delete-id = {{ $role->id}}  data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Delete Role"><i class="fa-sharp fa-solid fa-trash"></i></button>
        @endcan
    </div>
    @endcanany
@endif
