<?php

namespace App\Http\Controllers;

use App\Repositories\ContactRepositoryInterface;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Request;


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
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'email' => 'required|email|unique:contacts,email',
        ]);

        $contact = $this->contactRepository->create($data);
        return new ContactResource($contact);
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
            'phone' => 'sometimes|required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'email' => 'sometimes|required|email|unique:contacts,email,' . $id,
        ]);

        $contact = $this->contactRepository->find($id);
        if (!$contact) {
            return response()->json(['error' => 'Contact not found'], 404);
        }

        $updatedContact = $this->contactRepository->update($id, $data);

        return new ContactResource($updatedContact);
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
