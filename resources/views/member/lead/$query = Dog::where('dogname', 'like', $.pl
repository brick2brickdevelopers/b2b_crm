$query = Dog::where('dogname', 'like', $dogsch);
        if ($aval == "n") {
            $query->where('adopted', '=', 1);
        } else if ($aval == "y") {
            $query->where('adopted', '=', 0);
        }
        $dogs = $query->orderBy('lastedit', 'DESC')->paginate(5);




        $users = EloquentBuilder::to(User::class, $request->all());

        return $users->get();