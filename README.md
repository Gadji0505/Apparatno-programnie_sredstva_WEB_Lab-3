#include <iostream>
#include <string>
#include <cctype>

using namespace std;

string input;
size_t pos = 0;

void next_char() {
    if (pos < input.length() - 1) pos++;
    else pos = input.length();
}

void skip_whitespace() {
    while (pos < input.length() && isspace(input[pos])) next_char();
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
    if (c == '+' || c == '-') next_char();
    return true;
}

bool parse_real() {
    if (accept('.')) return parse_unsigned();

    parse_sign();

    if (!parse_unsigned()) return false;

    if (accept('.')) return parse_unsigned();

    return true;
}

bool parse_variable() {
    if (!isalpha(current_char())) return false;
    while (isalnum(current_char())) next_char();

    while (true) {
        if (accept('[')) {
            do {
                if (!parse_real() && !parse_variable()) return false;
            } while (accept(','));
            
            if (!accept(']')) return false;
        }
        else if (accept('.')) {
            if (!isalpha(current_char())) return false;
            while (isalnum(current_char())) next_char();
        }
        else if (accept('-') && accept('>')) {
            if (!isalpha(current_char())) return false;
            while (isalnum(current_char())) next_char();
        }
        else break;
    }
    
    return true;
}

bool parse_horner_scheme() {
    if (!parse_real() && !parse_variable()) return false;

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
    string tests[] = {
        "1", "1 + x(2)", "1 + x(2 + x(3))", "a + x(b + x(c))", "arr[1] + x(mat[2][3])",
        "1 + x(2 + x)", "1 + 2", "1 + x(2 + y(3))", "1 + x(2 + x(3)", "x + 1"
    };

    for (const auto& test : tests) {
        cout << "'" << test << "' -> " << (is_horner_scheme(test) ? "Да" : "Нет") << endl;
    }

    string line;
    while (getline(cin, line)) {
        if (line == "exit") break;
        cout << "'" << line << "' -> " << (is_horner_scheme(line) ? "Схема Горнера" : "Не схема Горнера") << endl;
    }

    return 0;
}