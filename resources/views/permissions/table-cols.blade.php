@if( $col == 'name' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Permission Name">{{$permissions->name ?? 'N/A'}}</span>
        
@elseif( $col == 'created_at' )
    <div class="@if(!auth()->user()->can('delete-permission') ) text-end  @endif">
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Created Date"> {{ showTwelveHourDateTime($permissions->created_at) }} </span>
    </div>
    
@elseif( $col == 'actions' )
    @can('delete-permission')
    <div class="text-end">
        <button type="button" class="btn btn-sm btn-icon btn-danger delete" data-delete-id = {{ $permissions->id}} data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Delete Permission"><i class="fa-sharp fa-solid fa-trash"></i></button>
    </div>
    @endcan
@endif
