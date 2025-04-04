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







### Упражнения:

#### 1. Решение систем линейных уравнений методом Гаусса с нахождением невязок  
Решите следующие системы методом Гаусса с частичным выбором ведущего элемента и найдите невязки (разности между свободными членами и результатами подстановки найденных значений в уравнения системы):  

**a)**  
\[
\begin{cases} 
10^{-4}x_1 + x_2 = 1; \\ 
x_1 + 2x_2 = 4. 
\end{cases}
\]

**b)**  
\[
\begin{cases} 
2,34x_1 - 4,21x_2 - 11,61x_3 = 14,41; \\ 
8,04x_1 + 5,22x_2 + 0,27x_3 = -6,44; \\ 
3,92x_1 - 7,99x_2 + 8,37x_3 = 55,56. 
\end{cases}
\]

**c)**  
\[
\begin{cases} 
4,43x_1 - 7,21x_2 + 8,05x_3 + 1,23x_4 - 2,56x_5 = 2,62; \\ 
-1,29x_1 + 6,47x_2 + 2,96x_3 + 3,22x_4 + 6,12x_5 = -3,97; \\ 
6,12x_1 + 8,31x_2 + 9,41x_3 + 1,78x_4 - 2,88x_5 = -9,12; \\ 
-2,57x_1 + 6,93x_2 - 3,74x_3 + 7,41x_4 + 5,55x_5 = 8,11; \\ 
1,46x_1 + 3,62x_2 + 7,83x_3 + 6,25x_4 - 2,35x_5 = 7,23. 
\end{cases}
\]

**Примечание:** Для уменьшения ошибок округления на каждом шаге прямого хода переставляйте строки так, чтобы деление выполнялось на наибольший по модулю элемент в столбце.  

---

#### 2. Исследование плохо обусловленных систем  
**a)** Решите систему:  
\[
\begin{pmatrix}
1.00 & 0.80 & 0.64 \\
1.00 & 0.90 & 0.81 \\
1.00 & 1.10 & 1.21
\end{pmatrix}
\begin{pmatrix}
x_1 \\
x_2 \\
x_3
\end{pmatrix}
=
\begin{pmatrix}
\text{erf}(0.80) \\
\text{erf}(0.90) \\
\text{erf}(1.10)
\end{pmatrix},
\]  
где \(\text{erf}(x)\) — функция ошибок (см. ЛР1).  

- Вычислите обусловленность матрицы \(A\):  
  \[
  \text{cond}(A) = \|A\| \cdot \|A^{-1}\|, \quad \|A\| = \max_{j=1}^n \|a_j\|.
  \]  
- Найдите невязку \(|Ax - b|\).  
- Сравните \(x_1 + x_2 + x_3\) с \(\text{erf}(1.0)\) и объясните близость значений.  

**b)** Для системы \(Ax = b\), где  
\[
A = 
\begin{pmatrix}
0.1 & 0.2 & 0.3 \\
0.4 & 0.5 & 0.6 \\
0.7 & 0.8 & 0.9
\end{pmatrix}, \quad
b = 
\begin{pmatrix}
0.1 \\
0.3 \\
0.5
\end{pmatrix},
\]  
покажите, что система имеет бесконечно много решений, и опишите их.  

---

#### 3. Решение СЛАУ итерационными методами  
Решите систему итерационными методами (Якоби и Зейделя):  
\[
\begin{cases}
12,14x_1 + 1,32x_2 - 0,78x_3 - 2,75x_4 = 14,78; \\
-0,89x_1 + 16,75x_2 + 1,88x_3 - 1,55x_4 = -12,14; \\
2,65x_1 - 1,27x_2 - 15,64x_3 - 0,64x_4 = -11,65; \\
2,44x_1 + 1,52x_2 + 1,93x_3 - 11,43x_4 = 4,26.
\end{cases}
\]  

**Требуется:**  
- Вывести решение.  
- Построить график зависимости нормы невязки от номера итерации.  
- Указать значение невязки при достижении заданной точности.  
- Провести вычисления для разных начальных приближений.  

**Примечание:** Метод Зейделя обычно сходится быстрее метода Якоби, если оба метода сходятся.
