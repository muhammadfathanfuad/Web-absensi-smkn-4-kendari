import pandas as pd

# Read the Excel file
df = pd.read_excel('public/data/jadwal senin.xlsx')

print("Data types for each column:")
print(df.dtypes)

print("\nFirst few rows for reference:")
print(df.head())

print("\nAll columns:")
print(df.columns.tolist())

print("\nShape of dataframe:")
print(df.shape)
