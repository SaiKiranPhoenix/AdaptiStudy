from flask import Flask, render_template, request, jsonify
import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.tree import DecisionTreeClassifier
from sklearn.ensemble import RandomForestClassifier
from sklearn.svm import SVC
from sklearn.neighbors import KNeighborsClassifier
from sklearn.metrics import accuracy_score

app = Flask(__name__)

# Load the dataset and train the models
data = pd.read_csv("dataset.csv")

X = data[['Q1', 'Q2', 'Q3', 'Q4', 'Q5', 'Q6', 'Q7','Q8']]
y = data['Learning Style']

X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

DC = DecisionTreeClassifier()
DC.fit(X_train, y_train)

RF = RandomForestClassifier()
RF.fit(X_train, y_train)

KNN = KNeighborsClassifier()
KNN.fit(X_train, y_train)

svc = SVC()
svc.fit(X_train, y_train)

# Choose the best model based on accuracy
models = {'DecisionTreeClassifier': DC,
          'RandomForestClassifier': RF,
          'KNeighborsClassifier': KNN,
          'SVC': svc}

best_model_name = max(models, key=lambda k: accuracy_score(y_test, models[k].predict(X_test)))
best_model = models[best_model_name]

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/predict', methods=['POST'])
def predict():
    try:
        input_data = [float(request.form[f'Q{i}']) for i in range(1, 9)]
        prediction = best_model.predict([input_data])
        return render_template('javacourse.html', prediction=prediction[0])
    except Exception as e:
        return str(e)

if __name__ == '__main__':
    app.run(debug=True)
