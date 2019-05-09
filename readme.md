# MyCheck - Movie Recommendation

## The Mission

1. Create endpoint for movies recommendations:

   - Create endpoint for movies recommendations
   - Create an endpoint that receives a movie ID, the returns other movies as
  recommendation, from the API.
   - The result of the recommendations should include only the id, name and release year of
the movie.
   - show only the first 3 recommended movies.
   
2. Add the option to get recommendation recursively:

   - The result of recommended movies will contain recommendations for them as well.
    So our original endpoint will not not only recieve movie ID, but also a “depth” param.
   - This should return 3 recommended movies for each of the 3 recommended movies, and
     for each result, its recommendations as well.
   - response expected example for depth 2 (pseudo code to demonstrate structure):
   ```javascript
    [
      {id:550,name:"fight club","release-year":"1999"
       ,recommendations:[
       {id: 680, name: “Pulp Fiction”,"release-year":"1994"},
       {...},
       {...},
       ]},
      {id:580,name:"fight without club","release-year":"2003"
      ,recommendations:[
      {id: 455, name: “Pulp function”,"release-year":"1984"},
      {...},
      {...}   
      ]}
    ]
   ```
   - max depth should be 3.
   
## Installation:

#### Summary

 1. this project was build with the following: 
    - Laravel 
    - Laradoc - A full PHP development environment for Docker
    
    for more information on how to install it, follow this [link](https://laradock.io/getting-started/) 
 
  In case you want insert this project inside your environment follow these steps:
   - open terminal
   - clone project from the following by typing `git clone https://github.com/BungHolem32/MyCheck` into you project folder
   - type `cp env-example .env`
   - type `composer install`
   - type `php artisan serve`
   - enter into this url http://localhost:8000

