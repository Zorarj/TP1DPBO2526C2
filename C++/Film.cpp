#include <iostream>
#include <string>
#include <vector>

using namespace std;

class Film {
public:
    int id;
    string name;
    string genre;
    int price;

    vector<Film> listFilm;

    // Konstruktor Default
    Film() {
        this->id = 0;
        this->name = "";
        this->genre = "";
        this->price = 0;
    }

    // Konstruktor Parameter
    Film(int id, string name, string genre, int price) {
        this->id = id;
        this->name = name;
        this->genre = genre;
        this->price = price;
    }

    void addFilm(int id, string name, string genre, int price) {
        if (id < 0 || price < 0) {
            cout << "Id dan Price tidak boleh mines!!" << endl;
        } else {
            Film newFilm(id, name, genre, price);
            this->listFilm.push_back(newFilm);
        }
    }

    void showAllFilm() {
        for (const auto& film : this->listFilm) {
            cout << "[" << film.id << "] " << film.name << " - " << film.genre << " (Rp" << film.price << ")" << endl;
        }
    }

    void showFilm(int id) {
        for (const auto& film : this->listFilm) {
            if (film.id == id) {
                cout << "[" << film.id << "] " << film.name << " - " << film.genre << " (Rp" << film.price << ")" << endl;
                return;
            }
        }
        cout << "Data film dengan id: " << id << " tidak ditemukan.." << endl;
    }

    void updateFilm(int id, string name, string genre, int price) {
        for (auto& film : this->listFilm) {
            if (film.id == id) {
                film.name = name;
                film.genre = genre;
                film.price = price;
                return;
            }
        }
        cout << "Data film dengan id: " << id << " tidak ditemukan.." << endl;
    }

    void removeFilm(int id) {
        for (size_t i = 0; i < this->listFilm.size(); i++) {
            if (this->listFilm[i].id == id) {
                // Menghapus elemen dari vector
                this->listFilm.erase(this->listFilm.begin() + i);
                cout << "Data film dengan id: " << id << " berhasil di hapus!!" << endl;
                return;
            }
        }
        cout << "Data film dengan id: " << id << " tidak ditemukan.." << endl;
    }
};

int main() {
    Film manajer;

    // Menambahkan data
    manajer.addFilm(1, "Inception", "Sci-Fi", 50000);
    manajer.addFilm(2, "Avengers", "Action", 60000);

    // Menampilkan semua film
    cout << "--- Semua Film ---" << endl;
    manajer.showAllFilm();

    // Menampilkan film berdasarkan ID
    cout << "\n--- Cari Film ID 1 ---" << endl;
    manajer.showFilm(1);

    // Update film
    cout << "\n--- Update Film ID 1 ---" << endl;
    manajer.updateFilm(1, "Inception Director's Cut", "Sci-Fi", 55000);
    manajer.showFilm(1);

    // Hapus film
    cout << "\n--- Hapus Film ID 2 ---" << endl;
    manajer.removeFilm(2);

    // Menampilkan semua film kembali
    cout << "\n--- Semua Film Setelah Dihapus ---" << endl;
    manajer.showAllFilm();

    return 0;
}