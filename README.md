#include <iostream>
#include <string>
#include <cctype>

using namespace std;

// Глобальные переменные для состояния парсера
string input;
size_t pos = 0;

// Вспомогательные функции
void next_char() {
    if (pos < input.length() - 1) {
        pos++;
    } else {
        pos = input.length();
    }
}

void skip_whitespace() {
    while (pos < input.length() && isspace(input[pos])) {
        next_char();
    }
}

char current_char() {
    return pos < input.length() ? input[pos] : '\0';
}

bool accept(char expected) {
    skip_whitespace();
    if (current_char() == expected) {
        next_char();
        return true;
    }
    return false;
}

// Функции для разбора по правилам
bool parse_digit() {
    if (isdigit(current_char())) {
        next_char();
        return true;
    }
    return false;
}

bool parse_unsigned() {
    if (!parse_digit()) return false;
    while (parse_digit());
    return true;
}

bool parse_sign() {
    char c = current_char();
    if (c == '+' || c == '-') {
        next_char();
    }
    return true;
}

bool parse_real() {
    if (accept('.')) {
        return parse_unsigned();
    }

    parse_sign();

    if (!parse_unsigned()) return false;

    if (accept('.')) {
        return parse_unsigned();
    }

    return true;
}

// Реализация правила для переменной с картинки
bool parse_variable() {
    // (переменная) ::= (имя) {[ (выражение) {, (выражение)} ]}
    // Упрощенная реализация для примера
    
    // Проверяем имя (упрощенно - буквы)
    if (!isalpha(current_char())) return false;
    while (isalnum(current_char())) next_char();

    while (true) {
        if (accept('[')) {
            // Разбор выражений в квадратных скобках
            do {
                if (!parse_horner_scheme()) return false;
            } while (accept(','));
            
            if (!accept(']')) return false;
        }
        else if (accept('.')) {
            // Обращение к полю (упрощенно)
            if (!isalpha(current_char())) return false;
            while (isalnum(current_char())) next_char();
        }
        else if (accept('-') && accept('>')) {
            // Стрелка (->)
            if (!isalpha(current_char())) return false;
            while (isalnum(current_char())) next_char();
        }
        else {
            break;
        }
    }
    
    return true;
}

bool parse_horner_scheme() {
    if (!parse_real()) {
        // Если не число, пробуем разобрать как переменную
        return parse_variable();
    }

    skip_whitespace();
    if (current_char() == '\0') return true;

    if (!accept('+')) return false;
    if (!accept('x')) return false;
    if (!accept('(')) return false;
    if (!parse_horner_scheme()) return false;
    if (!accept(')')) return false;

    return true;
}

bool is_horner_scheme(const string& str) {
    input = str;
    pos = 0;
    return parse_horner_scheme() && pos == input.length();
}

int main() {
    // Тесты для схемы Горнера
    string tests[] = {
        "1",                        // true
        "1 + x(2)",                 // true
        "1 + x(2 + x(3))",          // true
        "a + x(b + x(c))",          // true (с переменными)
        "arr[1] + x(mat[2][3])",    // true (с массивами)
        "1 + x(2 + x)",             // false
        "1 + 2",                    // false
        "1 + x(2 + y(3))",          // false
        "1 + x(2 + x(3)",           // false
        "x + 1"                     // false
    };

    cout << "Тестирование парсера схемы Горнера:\n";
    for (const auto& test : tests) {
        cout << "'" << test << "' -> " 
             << (is_horner_scheme(test) ? "Да" : "Нет") << endl;
    }

    // Интерактивный режим
    cout << "\nВведите выражение для проверки (или 'exit' для выхода):\n";
    string line;
    while (getline(cin, line)) {
        if (line == "exit") break;
        cout << "'" << line << "' -> " 
             << (is_horner_scheme(line) ? "Схема Горнера" : "Не схема Горнера") << endl;
    }

    return 0;
}