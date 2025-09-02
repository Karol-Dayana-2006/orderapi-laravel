<?php

namespace App\Http\Controllers;

use App\Models\Observation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ObservationController extends Controller
{
    private $rules= [
        'description'=> 'required|string|min:3|max:100'
    ];
    private $traductionAttributes = [
      'description' => 'Descripción'  
    ];


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $observations = Observation::all();
        return response()->json($observations, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->applyValidator($request, $this->rules, $this->traductionAttributes);
        if (!empty($dara)) 
        {
            return $data;
        }
        $observations = Observation::create($request->all());
        $response = [
            'message' => 'registro creado exitosamente',
            'causal' => $observations
        ];
        return response()->json($response, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Observation $observation)
    {
        return response()->json($observation, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Observation $observation)
    {
        $data = $this->applyValidator($request, $this->rules, $this->traductionAttributes);
        if (!empty($dara)) 
        {
            return $data;
        }
        $observation->update($request->all());
        $response = [
            'message' => 'registro actualizado exitosamente',
            'causal' => $observation
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Observation $observation)
    {
        $observation->delete();
        $response = [
            'message' => 'registro eliminado exitosamente',
            'causal' => $observation
        ];
        return response()->json($response, Response::HTTP_OK);
    }
}