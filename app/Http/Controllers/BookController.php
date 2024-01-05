<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;
use Symfony\Contracts\Service\Attribute\Required;

class BookController extends Controller
{
    public function welcome(){
        return view("welcome");
    }

    public function index(Request $request){

        if($request->has('search')){
        
 

        $books = Book::where('title', 'like', '%' . $request->search . '%')
    ->orWhere('author', 'like', '%' . $request->search . '%')
    ->paginate(10);


        }
        else{

        // fetch book data from book table
        $books = Book::paginate(10);
        }




        // pass books data to view


        return view("books.index")->with('books',$books);
    }

    public function show($book_id){
        $book = Book::find($book_id);

        return view("books.show")->with('book',$book);
    }

    public function create(){
        return view("books.create");
    }

    public function store(Request $request){

        $rules=[
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'isbn' => 'required|numeric|digits:13',
            'price' => 'required|numeric',
            'stock' => 'required|numeric|integer|min:0',
        ];

        $this->validate($request, $rules);
        //$request->validate($rules);

        $book = new Book;
        $book->title = $request->title;
        $book->author = $request->author;
        $book->isbn = $request->isbn;
        $book->price = $request->price;
        $book->stock = $request->stock;
        $book->save();

        return redirect()->route('books.show', $book->id);

    }


    public function edit($book_id){
        $book = Book::find($book_id);
        return view("books.edit")->with('book',$book);
    }

    public function update(Request $request){
        $rules=[
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'isbn' => 'required|numeric|digits:13',
            'price' => 'required|numeric',
            'stock' => 'required|numeric|integer|min:0',
        ];

        $this->validate($request, $rules);
        //$request->validate($rules);

        $book = Book::find($request->id);

        $book->title = $request->title;
        $book->author = $request->author;
        $book->isbn = $request->isbn;
        $book->price = $request->price;
        $book->stock = $request->stock;
        $book->save();

        return redirect()->route('books.show', $book->id);


    }

    public function destroy(Request $request){
        $book = Book::find($request->id);

        $book->delete();

        return redirect()->route('books.index', $book->id);


    }
}
