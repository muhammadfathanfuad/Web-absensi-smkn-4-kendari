import pandas as pd

# Read the Excel file
df = pd.read_excel('public/data/data guru.xlsx')

print("Data types for each column:")
print(df.dtypes)

print("\nFirst few rows for reference:")
print(df.head())
