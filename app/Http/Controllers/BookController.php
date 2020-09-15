<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Requests\BookRequest;
use App\Jobs\SendMail;
use App\Traits\ResponseHandleTrait;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookController extends Controller
{

    use ResponseHandleTrait;

 public function index()
    {
    DB::beginTransaction();
    try {
        $user = Auth::user();
       $allBook =$user->books;
       DB::commit();
       return $this->HandleResponseWithData($allBook,true,"Book Fetched SuccessFully",201);
      }catch(ModelNotFoundException $e)
      {
       DB::rollback();
       return $this->HandleResponse(false,$e->getMessage(),404);
      } catch(\Exception $e){
          DB::rollback();
          throw $e;
    }
    }

    public function store(BookRequest $request)
    {
        DB::beginTransaction();
        try {
            // Validate, then create if valid
            $request["user_id"] = Auth::id();
            $newBook = Book::create($request->all());
            DB::commit();
            $action = "Created";
            $details = ["email" => Auth::user()->email];
            $user= User::find($newBook->user_id);
            $email = SendMail::dispatch($details,$newBook,$user,$action);
            if($email) {
               return $this->HandleResponseWithData($newBook,true,"Book Added SuccessFully",201);
            }
        } catch(ValidationException $e)
        {
            DB::rollback();
            return $this->HandleResponse(false,$e->getMessage(),500);

        } catch(\Exception $e)
        {
            DB::rollback();
            throw $e;
        }
    }
 public function show($id)
    {
        DB::beginTransaction();
        try {
           $book = Book::find($id);
           DB::commit();
           if(!is_null($book)){
           return $this->HandleResponseWithData($book,true,"Singe Book Fetched SuccessFully",201);
           }else{
            return $this->HandleResponse(false,"Book Not Found",404);
           }
          }catch(ModelNotFoundException $e)
          {
           DB::rollback();
           return $this->HandleResponse(false,$e->getMessage(),404);
          } catch(\Exception $e){
              DB::rollback();
              throw $e;
        }
    }

    public function update(BookRequest $request, $id)
    {
        DB::beginTransaction();
        try {
           $book = Book::where("id",$id)->update($request->all());
           DB::commit();
           $updatedbook = Book::find($id);
            $action = "Updated";
            $details = ["email" => Auth::user()->email];
            $user= User::find($updatedbook->user_id);
            $email = SendMail::dispatch($details,$updatedbook,$user,$action);
            if($email){
           return $this->HandleResponse(true,"Book Updated SuccessFully",201);
            }
          }catch(ValidationException  $e)
          {
           DB::rollback();
           return $this->HandleResponse(false,$e->getMessage(),404);
          } catch(\Exception $e){
              DB::rollback();
              throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $deletedbook = Book::find($id);
            $action = "Deleted";
            $details = ["email" => Auth::user()->email];
            $user= User::find($deletedbook->user_id);
            $email = SendMail::dispatch($details,$deletedbook,$user,$action);
           $book = Book::where("id",$id)->delete();
           DB::commit();
            if($email){
           return $this->HandleResponse(true,"Book Deleted SuccessFully",201);
            };
          }catch(ModelNotFoundException  $e)
          {
           DB::rollback();
           return $this->HandleResponse(false,$e->getMessage(),404);
          } catch(\Exception $e){
              DB::rollback();
              throw $e;
        }
    }

    public function PermenentDelete($id){
        DB::beginTransaction();
        try {
        $book = Book::onlyTrashed()->find($id);
        DB::commit();
        if (!is_null($book)) {
            $book->forceDelete();
            return $this->HandleResponse(true,"Book Deleted Permenently",200);
        } else {
            return $this->HandleResponse(false,"Error in Permenent Deletion",500);
        }
    }catch(ModelNotFoundException  $e)
    {
     DB::rollback();
     return $this->HandleResponse(false,$e->getMessage(),404);
    } catch(\Exception $e){
        DB::rollback();
        throw $e;
  }

    }

   public function restore($id){
    DB::beginTransaction();
    try {
    $book = Book::onlyTrashed()->find($id);
     DB::commit();
    if (!is_null($book)) {

        $book->restore();
        return $this->HandleResponse(true,"Book Restore Permenently",200);
    } else {

        return $this->HandleResponse(false,"Error in Restore Book",500);
    }
  }catch(ModelNotFoundException  $e)
  {
   DB::rollback();
   return $this->HandleResponse(false,$e->getMessage(),404);
  } catch(\Exception $e){
      DB::rollback();
      throw $e;
}

   }

   public function softDeleted()
    {
    DB::beginTransaction();
    try {
        $books = Book::onlyTrashed()->where('user_id', Auth::id())->get();
        DB::commit();
        return $this->HandleResponseWithData($books,true,"Book Only SoftDelete Fetched",200);
    }catch(ModelNotFoundException  $e)
    {
     DB::rollback();
     return $this->HandleResponse(false,$e->getMessage(),404);
    } catch(\Exception $e){
        DB::rollback();
        throw $e;
  }
}
}
