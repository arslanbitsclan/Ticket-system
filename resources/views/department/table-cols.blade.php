@if( $col == 'name' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Department Name">{{$department->name ?? 'N/A'}}</span>
    
@elseif( $col == 'created_at' )
    <div class="@if(!auth()->user()->can('edit-department') && !auth()->user()->can('delete-department') ) text-end  @endif">
        <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Created Date"> {{ showTwelveHourDateTime($department->created_at) }} </span>
    </div>

@elseif( $col == 'actions' )
    @canany(['edit-department','delete-department'])
    <div class="text-end">
        @can('edit-department')
        <a href="{{ route('department.edit', $department->id) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Edit Role"><i class="fa-solid fa-pen-to-square"></i></a>
        @endcan
        
        @can('delete-department')
        <button type="button" class="btn btn-sm btn-icon btn-danger delete" data-delete-id = {{ $department->id}} data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Delete Role"><i class="fa-sharp fa-solid fa-trash"></i></button>
        @endcan
    </div>
    @endcanany
@endif
