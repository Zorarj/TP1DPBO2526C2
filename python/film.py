class Film:
    def __init__(self, id=None, name=None, genre=None, price=None):
        self.id = id
        self.name = name
        self.genre = genre
        self.price = price

        # List untuk menyimpan objek-objek Film
        self.list_film = []

    def add_film(self, id, name, genre, price):
        if id < 0 or price < 0:
            print("Id dan Price tidak boleh mines!!")
        else:
            new_film = Film(id, name, genre, price)
            self.list_film.append(new_film)

    def show_all_film(self):
        for film in self.list_film:
            print(f"[{film.id}] {film.name} - {film.genre} (Rp{film.price})")

    def show_film(self, id):
        for film in self.list_film:
            if film.id == id:
                print(f"[{film.id}] {film.name} - {film.genre} (Rp{film.price})")
                return
        print(f"Data film dengan id: {id} tidak ditemukan..")

    def update_film(self, id, name, genre, price):
        for film in self.list_film:
            if film.id == id:
                film.name = name
                film.genre = genre
                film.price = price
                return
        print(f"Data film dengan id: {id} tidak ditemukan..")

    def remove_film(self, id):
        for i, film in enumerate(self.list_film):
            if film.id == id:
                del self.list_film[i]
                print(f"Data film dengan id: {id} berhasil di hapus!!")
                return
        print(f"Data film dengan id: {id} tidak ditemukan..")


# --- CONTOH PENGGUNAAN ---
if __name__ == "__main__":
    manajer = Film()

    # Menambahkan data
    manajer.add_film(1, "Inception", "Sci-Fi", 50000)
    manajer.add_film(2, "Avengers", "Action", 60000)

    # Menampilkan semua film
    print("--- Semua Film ---")
    manajer.show_all_film()

    # Menampilkan film berdasarkan ID
    print("\n--- Cari Film ID 1 ---")
    manajer.show_film(1)

    # Update film
    print("\n--- Update Film ID 1 ---")
    manajer.update_film(1, "Inception Director's Cut", "Sci-Fi", 55000)
    manajer.show_film(1)

    # Hapus film
    print("\n--- Hapus Film ID 2 ---")
    manajer.remove_film(2)

    # Menampilkan semua film kembali
    print("\n--- Semua Film Setelah Dihapus ---")
    manajer.show_all_film()