#include <iostream>
#include <string>
using namespace std;

// 1. Функция проверки цифры
bool is_digit(char c) {
    return c >= '0' && c <= '9';
}

// 2. Функция пропуска пробелов
void skip_spaces(const string& s, int& pos) {
    while (pos < s.size() && s[pos] == ' ') {
        pos++;
    }
}

// 3. Функция проверки числа
bool check_number(const string& s, int& pos) {
    // Проверяем знак числа
    if (s[pos] == '+' || s[pos] == '-') {
        pos++;
    }
    
    // Проверяем цифры целой части
    bool has_digits = false;
    while (pos < s.size() && is_digit(s[pos])) {
        pos++;
        has_digits = true;
    }
    
    // Если нет ни одной цифры - ошибка
    if (!has_digits) return false;
    
    // Проверяем дробную часть (если есть)
    if (pos < s.size() && s[pos] == '.') {
        pos++;
        // После точки должна быть хотя бы одна цифра
        if (pos >= s.size() || !is_digit(s[pos])) return false;
        while (pos < s.size() && is_digit(s[pos])) {
            pos++;
        }
    }
    
    return true;
}

// 4. Основная функция проверки схемы Горнера
bool check_horner(const string& s, int& pos) {
    // Пропускаем пробелы в начале
    skip_spaces(s, pos);
    
    // Проверяем первое число
    if (!check_number(s, pos)) {
        return false;
    }
    
    // Пропускаем пробелы после числа
    skip_spaces(s, pos);
    
    // Если строка закончилась - это корректная схема
    if (pos >= s.size()) {
        return true;
    }
    
    // Проверяем наличие "+x("
    if (s[pos] != '+') return false;
    pos++;
    if (pos >= s.size() || s[pos] != 'x') return false;
    pos++;
    if (pos >= s.size() || s[pos] != '(') return false;
    pos++;
    
    // Рекурсивно проверяем внутреннюю часть
    if (!check_horner(s, pos)) {
        return false;
    }
    
    // Проверяем закрывающую скобку
    if (pos >= s.size() || s[pos] != ')') return false;
    pos++;
    
    return true;
}

// 5. Функция для проверки всей строки
bool is_horner_scheme(const string& s) {
    int pos = 0;
    bool is_valid = check_horner(s, pos);
    
    // Проверяем, что дошли до конца строки
    skip_spaces(s, pos);
    return is_valid && (pos == s.size());
}

// 6. Главная функция программы
int main() {
    cout << "Проверка схемы Горнера" << endl;
    cout << "Примеры правильных схем:" << endl;
    cout << "1 + x(2)" << endl;
    cout << "3.5 + x(0 + x(1.2))" << endl;
    cout << "--------------------------------" << endl;
    
    string input;
    cout << "Введите выражение для проверки: ";
    getline(cin, input);
    
    // 7. Проверка введенной строки
    if (is_horner_scheme(input)) {
        cout << ">>> Это корректная схема Горнера!" << endl;
    } else {
        cout << ">>> Это НЕ схема Горнера!" << endl;
    }
    
    return 0;
}