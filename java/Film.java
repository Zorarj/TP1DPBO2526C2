public class Film{
    public int id;
    public String name;
    public String genre;
    public int price;

    public Film[] listFilm;
    public int num;

    public Film(){
        this.listFilm = new Film[100];
        this.num = 0;
    }

    public Film(int id, String name, String genre, int price){
        this.id = id;
        this.name = name;
        this.genre = genre;
        this.price = price;
    }

    public void addFilm(int id, String name, String genre, int price){
        if(id < 0 || price < 0){
            System.out.println("Id dan Price tidak boleh mines!!");
        }else{
            this.listFilm[num] = new Film(id,name,genre,price);
            this.num++;
        }
    }

    public void showAllFilm(){
        for(int i = 0; i < this.num; i++){
            System.out.println("[" + this.listFilm[i].id + "] " + this.listFilm[i].name + " - " + this.listFilm[i].genre + " (Rp" + this.listFilm[i].price + ")");
        }
    }

    public void showFilm(int id){
        for(int i = 0; i < this.num; i++){

            if(id == this.listFilm[i].id){
                System.out.println("[" + this.listFilm[i].id + "] " + this.listFilm[i].name + " - " + this.listFilm[i].genre + " (Rp" + this.listFilm[i].price + ")");
                return; 
            }
        }
        System.out.println("Data film dengan id: " + id + " tidak ditemukan..");
    }

    public void updateFilm(int id, String name, String genre, int price){
        for(int i = 0; i < this.num; i++){
            if(id == this.listFilm[i].id){
                this.listFilm[i].name = name;
                this.listFilm[i].genre = genre;
                this.listFilm[i].price = price;
                return; 
            }
        }
        System.out.println("Data film dengan id: " + id + " tidak ditemukan..");
    }

    public void removeFilm(int id){
        int i = 0;
        boolean found = false;
        while(i < this.num && found == false){
            if(id == this.listFilm[i].id){
                found = true;
            }else{
                i++;
            }
        }
        //geser
        for(int j = i; j < this.num - 1; j++){
            this.listFilm[j] = this.listFilm[j + 1]; 
        }
        this.listFilm[this.num - 1] = null;
        this.num--;

        if(found == true){
            System.out.println("Data film dengan id: " + id + " berhasil di hapus!!");
        }else{
            System.out.println("Data film dengan id: " + id + " tidak ditemukan..");
        }
    }
}

