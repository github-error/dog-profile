#include <stdio.h>
#include <stdlib.h>
#include <ctype.h>
#include <string.h>

int prec(char c) {
    
    if (c == '^')
        return 3;
    else if (c == '/' || c == '*')
        return 2;
    else if (c == '+' || c == '-')
        return 1;
    else
        return -1;
}

void infixToPostfix(char* exp) {
    int len = strlen(exp);
    char result[len + 1];
    char stack[len];
    int j = 0;
    int top = -1;

    for (int i = 0; i < len; i++) {
        char c = exp[i];

        // If the scanned character is 
        // an operand, add it to the output string.
        if (isalnum(c))
            result[j++] = c;

        // If the scanned character is
        // an ‘(‘, push it to the stack.
        else if (c == '(')
            stack[++top] = '(';

        // If the scanned character is an ‘)’,
        // pop and add to the output string from the stack 
        // until an ‘(‘ is encountered.
        else if (c == ')') {
            while (top != -1 && stack[top] != '(') {
                result[j++] = stack[top--];
            }
            top--; 
        }

        // If an operator is scanned
        else {
            while (top != -1 && (prec(c) < prec(stack[top]) ||
                                 prec(c) == prec(stack[top]))) {
                result[j++] = stack[top--];
            }
            stack[++top] = c;
        }
    }

    // Pop all the remaining elements from the stack
    while (top != -1) {
        result[j++] = stack[top--];
    }

    result[j] = '\0';
    printf("%s\n", result);
}

int main() {
    char exp[] = "a+b*(c^d-e)^(f+g*h)-i";
    infixToPostfix(exp);
    return 0;
}