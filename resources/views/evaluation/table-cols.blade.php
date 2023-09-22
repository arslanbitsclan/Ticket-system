@if( $col == 'type' )
    <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Evaluation type">{{$evaluation->type ?? 'N/A'}}</span>

@elseif( $col == 'created_at' )
    <div class="@if(!auth()->user()->can('edit-evaluation') && !auth()->user()->can('delete-evaluation') ) text-end  @endif">
        <span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Created date"> {{ showTwelveHourDateTime($evaluation->created_at) }} </span>
    </div>

@elseif( $col == 'actions' )
    @canany(['edit-evaluation','delete-evaluation'])
    <div class="text-end">
        @can('edit-evaluation')
        <a href="{{ route('evaluation.edit', $evaluation->id) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Edit Evaluation"><i class="fa-solid fa-pen-to-square"></i></a>
        @endcan
        
         @can('delete-evaluation')
        <button type="button" class="btn btn-sm btn-icon btn-danger delete" data-delete-id = {{ $evaluation->id}} data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Delete Evaluation"><i class="fa-sharp fa-solid fa-trash"></i></button>
        @endcan
    </div>
    @endcanany
@endif
