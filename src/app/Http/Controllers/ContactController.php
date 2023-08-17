<?php

namespace App\Http\Controllers;

use App\Repositories\ContactRepositoryInterface;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Jobs\ProcessContactCreation;
use App\Jobs\ProcessContactUpdate;


class ContactController extends Controller
{

    protected $contactRepository;

    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ContactResource::collection($this->contactRepository->all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'sometimes|required|string|max:255',
            'email' => 'required|email|unique:contacts,email',
        ]);

        ProcessContactCreation::dispatch($data);

        return response()->json(['message' => 'Contact creation process started!'], 202);
    }

    /**
     * Find the specified resource in storage.
     */
    public function show($id)
    {
        $contact = $this->contactRepository->find($id);
        if (!$contact) {
            return response()->json(['error' => 'Contact not found'], 404);
        }

        return new ContactResource($contact);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:contacts,email,' . $id,
        ]);

        $contact = $this->contactRepository->find($id);
        if (!$contact) {
            return response()->json(['error' => 'Contact not found'], 404);
        }

        ProcessContactUpdate::dispatch($id, $data);

        return response()->json(['message' => 'Contact update process started!'], 202);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $contact = $this->contactRepository->find($id);
        if (!$contact) {
            return response()->json(['error' => 'Contact not found'], 404);
        }

        $this->contactRepository->delete($id);

        return response()->json(null, 204);
    }
}
