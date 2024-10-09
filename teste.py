import tkinter as tk

def adicionar(valor):
    entrada.insert(tk.END, valor)

def calcular():
    try:
        resultado = eval(entrada.get())
        entrada.delete(0, tk.END)  # Limpa a entrada
        entrada.insert(tk.END, str(resultado))  # Mostra o resultado
    except Exception:
        entrada.delete(0, tk.END)
        entrada.insert(tk.END, "Erro")

def limpar():
    entrada.delete(0, tk.END)

# Criação da janela principal
root = tk.Tk()
root.title("Calculadora")

# Campo de entrada
entrada = tk.Entry(root, width=30, borderwidth=5)
entrada.grid(row=0, column=0, columnspan=4)  # Ocupa a primeira linha

# Botões
botoes = [
    ('7', 1, 0), ('8', 1, 1), ('9', 1, 2), ('/', 1, 3),
    ('4', 2, 0), ('5', 2, 1), ('6', 2, 2), ('*', 2, 3),
    ('1', 3, 0), ('2', 3, 1), ('3', 3, 2), ('-', 3, 3),
    ('C', 4, 0), ('0', 4, 1), ('=', 4, 2), ('+', 4, 3)
]

# Adiciona os botões na interface
for (texto, linha, coluna) in botoes:
    if texto == 'C':
        tk.Button(root, text=texto, padx=20, pady=20, command=limpar).grid(row=linha, column=coluna)
    elif texto == '=':
        tk.Button(root, text=texto, padx=20, pady=20, command=calcular).grid(row=linha, column=coluna)
    else:
        tk.Button(root, text=texto, padx=20, pady=20, command=lambda b=texto: adicionar(b)).grid(row=linha, column=coluna)

# Iniciar o loop da interface
root.mainloop()
